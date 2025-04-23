---
title: "Gateways"
---

Gateways are responsible for actually sealing and then sending a `Parcel` and issuing a `Receipt` for it.
The Notification Center ships with a `MailerGateway` which sends a `Parcel` using the Symfony Mailer.
Hence, the basic logic looks like this:

```php
class MailerGateway implements GatewayInterface
{
    public const NAME = 'mailer';
    
    public function __construct(private MailerInterface $mailer) 
    {
    
    }

    public function getName(): string
    {
        return self::NAME;
    }
    
    public function sealParcel(Parcel $parcel): Parcel
    {
        return $parcel
            ->seal()
            ->withStamp($this->createEmailStamp($parcel))
        ;
    }
    
    public function sendParcel(Parcel $parcel): Receipt
    {
        $email = $this->createEmail($parcel->getStamp(EmailStamp::class));

        try {
            $this->mailer->send($email);

            return Receipt::createForSuccessfulDelivery($parcel);
        } catch (TransportExceptionInterface $e) {
            return Receipt::createForUnsuccessfulDelivery(
                $parcel,
                CouldNotDeliverParcelException::becauseOfGatewayException(
                    self::NAME,
                    0,
                    $e
                )
            );
        }
    }
    
    private function createEmailStamp(Parcel $parcel): EmailStamp
    {
        // Create a stamp that contains all we need to actually send the e-mail
    }
    
    private function createEmail(EmailStamp $emailStamp): Symfony\Component\Mime\Email
    {
        // Create a Symfony Email instance based on our immutable EmailStamp
    }
}
```

> ⚠️ Notice how `$parcel->seal()` is called **before** adding the `EmailStamp`. This is an important design decision
> as it allows to make a difference between stamps that were added before and after the sealing process. The difference
> is that if you call `$parcel->unseal()`, the `EmailStamp` will not be present on the parcel. Only the stamps that have
> been added before are.

🚨 Let's talk about **the single most important** design decision when creating your own gateway which you
absolutely have to keep in mind: Your gateway **must not** rely on dynamic information in the `sendParcel()`
method. It **must be immutable**. Let's take the post office analogy: When you prepare your parcel, you can stick as many stamps and labels to
it. You can put placeholder stamps, unpack it, change its content, hand it to your friend to add more content or their own
labels etc. All of which is represented in the Notification Center by the `CreateParcelEvent`. However,
once you go to the counter and you actually want to send the parcel, you have to create one final version it. You cannot send it with `##receiver_name##` written on it and it cannot be sent when still open.
Thus, the parcel must be sealed. This is what you do in your `sealParcel()` method. Basically, this
is the one that does the heavy work. In most cases, you will take all the stamps, process them the way you want and
add another **immutable** stamp that `sendParcel()` will then use. This is exactly what happens in the example above.

The best way to think about this architectural design is to imagine that `sealParcel()` does not happen on the
same server as `sendParcel()`. This will clarify that everything `sendParcel()` requires, must be part of your
`Parcel` and its stamps.

Typical design issues may include:

* Accessing the current request via the `RequestStack` in the `sendParcel()` method. That is not allowed! If you
  need something from the current request, it's best to create a stamp for that. Use the `CreateParcelEvent` for it.
* Replacing insert tags in the `sendParcel()` method. This must happen in the `sealParcel()` method. An insert tag
  could be e.g. `{{env::request}}` which contains the URL of the current page. This might not exist during `sendParcel()`
  because it happens later/on a different server etc. Make sure you replace that information when sealing the parcel.

You can also extend `AbstractGateway` which provides helpers if your gateway e.g. requires certain stamps
to be present on your `Parcel`. E.g. the `MailerGateay` requires a `LanguageConfigStamp` to be present during the
`sealParcel()` stage, because it expects language specific information. And it expects an `EmailStamp` during
the `sendParcel()` stage. However, the `TokenCollectionStamp` is optional - it's also perfectly able
to send a `Parcel` without any token replacements.

Maybe you want to write a `SlackGateway` and you need some kind of `SlackTargetChannelStamp`?

The `AbstractGateway` also provides a simple `replaceTokens(Parcel $parcel, string $value)` method which will replace
tokens in case your Gateway was provided with Contao's `SimpleTokenParser` and the parcel has a `TokenCollectionStamp`.

In order to make your new gateway known to the Notification Center, you have to register it as a
service and tag it using the `notification_center.gateway` tag. If you use the [autoconfiguration
feature of the Symfony Container][DI_Autoconfigure], you don't need to tag the service. Implementing the
`GatewayInterface` will be enough.

## Asynchronous Gateways

When a parcel is given to a Gateway, there's an immediate response on the counter which is called the `Receipt`, we have
already learned about that. And this delivery can be either successful or unsuccessful. Either you managed to hand over
your parcel on the counter, or you didn't because e.g. the nice employee told you, you forgot to add a certain `Stamp`.

Now, what happens to that parcel after you have successfully delivered it to the counter? Exactly, so far, we have no clue.
The immediate `Receipt` only tells us whether our parcel has been accepted by the Gateway or not. But we are also interested
in whether the parcel was actually delivered to the final destination which can be minutes, days or weeks later.

Enter the `AsynchronousReceipt`.

The `MailerGateway` of the Notification Center uses this feature too because by default, Contao uses the Symfony Mailer
and sending the e-mails happens asynchronously using Symfony Messenger. This means that Contao is going to try to send
the e-mail in the background for a few times in order to work around temporary network outages etc.

Hence, when sealing the package, the `MailerGateway` adds another stamp in order to inform any third-party event listeners
about the fact, that this parcel will get asynchronous information:

```php
return $parcel
    ->seal()
    ->withStamp(AsynchronousDeliveryStamp::createWithRandomId())
;
```

Now, any listener can access this stamp using `$event->receipt->getParcel()->getStamp(AsynchronousDeliveryStamp::class)?->identifier`
in any of the events and store this identifier for further processing.

The `MailerGatway` itself passes this identifier as a header on the `Email` instance and - because it uses the Symfony Mailer -
registers to the Symfony Mailer `SentMessageEvent` and the `FailedMessageEvent`. This allows it to extract the header, remove
it from the final `Email` and inform any third-party integrators about the fact, that this e-mail now has been sent.
Doing this is pretty straightforward:

```php
$receipt = $error
    ? AsynchronousReceipt::createForUnsuccessfulDelivery($messageId, $error)
    : AsynchronousReceipt::createForSuccessfulDelivery($messageId);

$this->notificationCenter->informAboutAsynchronousReceipt($receipt);
```

The `NotificationCenter::informAboutAsynchronousReceipt()` does nothing more than dispatching an `AsynchronousReceiptEvent`
so listening to it is enough to get informed about any `AsynchronousReceipt`.

As you can see, you can enhance your Gateway with asynchronous capabilities in order to inform third-party developers about
the fact that your `Parcel` is actually sent asynchronously, you gave it an identifier, and you will inform them as soon
as you know the asynchronous process has finished.