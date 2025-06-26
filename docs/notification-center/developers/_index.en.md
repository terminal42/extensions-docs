---
title: "Developers"
---

The Notification Center has been built with developers in mind. Its main purpose is to provide a solution for
the typical "Dear customer, what would you like to have the e-mail notification say and how should it be translated
into French, German etc.?" question.

It gives you the opportunity to create "notification types" with its respective "simple tokens" with which users can
then write and translate their messages (e-mails, SMS, Slack messages, basically any Gateway is possible) - and have
complete freedom. All you need to do is to provide a notification type and a token configuration for it and the problem
is solved for you.

Hence, sending a message through the Notification Center is pretty straight forward:

```php
<?php

namespace Acme;

use Terminal42\NotificationCenterBundle\NotificationCenter;

class SomeService
{
    public function __construct(private NotificationCenter $notificationCenter) {}
    
    public function sendMessage(): void
    {
        $notificationId = 42: // Usually, some module setting of yours where the user can select the desired notification
        $tokens = [
            'firstname' => 'value1',
            'lastname' => 'value2',   
        ];
        
        $receipts = $this->notificationCenter->sendNotification($notificationId, $tokens);
    }
}
```

This will send the notification ID `42` with a "token collection" created based on your `my-notification-type-name` and
two raw tokens `token1` and `token2`. This means, your customer can go ahead and e.g. compose an e-mail saying
`Hello ##firstname## ##lastname##, welcome to my message`.

We'll get to the notification type in a second but let's first check some more internal concepts.

## Parcels and Receipts

The naming of classes within the Notification Center is designed around the concept of sending regular mail. As if
you'd go to your local post office. Hence, the actual thing that is sent, is referred to as `Parcel` and the result
of sending is, is your `Receipt`. As, sometimes, you are sending multiple parcels at once, there's also a `ParcelCollection`
and the corresponding `ReceiptCollection`.

Every `Parcel` has a `MessageConfig` which represents its contents. Moreover, it can have stamps which are represented by
the `StampInterface`. It can have as many stamps as needed and they represent metadata to the parcel itself.

Because there is one `Receipt` per `Parcel`, you can always access the original information and inspect everything you
need. So in our example above, `$receipts` is a `ReceiptCollection` and we can now do all kinds of operations with it:

```php
$receipts = $this->notificationCenter->sendNotification($notificationId, $tokenCollection);
        
// Successful, let's go!
if ($receipts->wereAllDelivered()) {
    return;
}

// Otherwise, we can inspect:
/** @var Terminal42\NotificationCenterBundle\Receipt\Receipt $receipt */
foreach ($receipts as $receipt) {
    if (!$receipt->wasDelivered()) {
       dump($receipt->getException()); // Always an instance of CouldNotDeliverParcelException
    }
    
    $receipt->getParcel()->getMessageConfig(); // Access the contents of the parcel
    $receipt->getParcel()->getStamps(); // Access the metadata of the parcel, aka the stamps
}
```

So calling `$this->notificationCenter->sendNotification()` is actually an abbreviation for creating a `Parcel` with
a `MessageConfig` and adding a `TokenCollectionStamp` which contains your token information.

The task of a gateway implementing the `GatewayInterface` then is to deliver that `Parcel` and returning
a `Receipt` for it. Gateways have [their dedicated documentation page]({{% ref "gateways" %}}).

For now, let's look at notification types.

## Notification types

Notification types define which tokens are available for a given message. This allows the Notification Center to
assist the users with autocompletion in the back end. Let's look at the one for the Contao form submission
notification type (triggered, when a form is submitted):

```php 
class FormGeneratorNotificationType implements NotificationTypeInterface
{
    public const NAME = 'core_form';

    public function __construct(private TokenDefinitionFactoryInterface $factory)
    {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenDefinitions(): array
    {
        return [
            $this->factory->create(AnythingTokenDefinition::class, 'form_*', 'form.form_*'),
            $this->factory->create(AnythingTokenDefinition::class, 'formconfig_*', 'form.formconfig_*'),
            $this->factory->create(AnythingTokenDefinition::class, 'formlabel_*', 'form.formlabel_*'),
            $this->factory->create(TextTokenDefinition::class, 'raw_data', 'form.raw_data'),
            $this->factory->create(TextTokenDefinition::class, 'raw_data_filled', 'form.raw_data_filled'),
        ];
    }
}
```

As you can see, it has a name (`core_form`) which we put in a constant, so it's easier to reuse when sending (instead
of typing `contao_form` you just use `FormGeneratorNotificationType::NAME`). Also, we use the `TokenDefinitionFactoryInterface` to
create our token definitions.

In order to make the new notification type known to the Notification Center, you have to register it as a
service and tag it using the `notification_center.notification_type` tag. If you use the [autoconfiguration
feature of the Symfony Container][DI_Autoconfigure], you don't need to tag the service. Implementing the
`NotificationTypeInterface` will be enough.

## Token definitions

A token definition has to implement the `TokenDefinitionInterface` and you can extend the `AbstractTokenDefinition` if you
need to create your own one. This, however, is pretty unlikely as the Notification Center already ships quite a few of
them:

* EmailTokenDefinition
* FileTokenDefinition
* HtmlTokenDefinition
* TextTokenDefinition
* AnythingTokenDefinition (basically "this token can be used anywhere")

Basically, the purpose of a token definition is to describe its values. For example, in the e-mail settings for
the recipient, we don't want anything different from `EmailTokenDefinition` instances to be allowed. You cannot send an e-mail
to `<html><title>Foobar</title></html>` - it must be an e-mail address.

In the DCA, you can then configure which token definition context is required:

```php
'recipients' => [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'long clr', 'decodeEntities' => true, 'mandatory' => true],
    'nc_context' => TokenContext::Email,
    'sql' => ['type' => 'string', 'length' => 255, 'default' => null, 'notnull' => false],
],
```

So we now have a list of token definitions and a token context "email". Someone now has to tell the Notification Center
that for this context, all tokens of type `EmailTokenDefinition` and `AnythingTokenDefinition` should be listed. See the
`GetTokenDefinitionClassesForContextEvent` for more details.

## Bulky items

To stick to our post office analogy: Sometimes parcels are really heavy or bulky and they cannot be handed
over the counter. So what you do is you hand over your bulky item at a separate place that has more space for
big items and trucks to maneuver. You may deliver your item there and receive a voucher for it. Then, you take
that voucher and take that to the post office counter to conclude the transaction.

Bulky items in web development might be things like user uploads, file attachments etc. We don't want to log the
contents of those files anywhere so they must not be part of our `Parcel` instance but instead we just pass along
"vouchers". Vouchers are nothing else than a simple combination of today's date and a UUID for later reference.

Let's look at how the Core uses this to pass on file uploads from the form generator to e.g. our mailer gateway:

```php
<?php

foreach ($files as $k => $file) {
    $voucher = $this->notificationCenter->getBulkyItemStorage()->store(
        FileItem::fromPath($file['tmp_name'], $file['name'], $file['type'], $file['size'])
    );

    $bulkyItemVouchers[] = $voucher;
}
```

As you can see. All it's doing is asking the Notification Center for the bulky goods storage and storing a `FileItem` in
there. It can be anything implementing the `BulkyItemInterface`. The Notification Center will take care of actually
storing the item and arbitrary metadata. It also takes care of cleaning them up. As a developer, that's all you need to do.
We then add a stamp to the parcel to inform the gateways about the fact that this parcel has bulky items to it:

```php
<?php

$stamps = $stamps->with(new BulkyItemsStamp($bulkyItemVouchers));
```

Done. A gateway can now use those vouchers to actually ask for the bulky items when sending them. This could
look for example like this:

```php
<?php

$item = $this->getNotificationCenter()->getBulkyItemStorage()->retrieve($voucher);

if ($item instanceof FileItem) {
    $email->attach(
        $item->getContents(),
        $item->getName(),
        $item->getMimeType()
    );
}
```

> 💡 Technically, you could omit the `BulkyItemsStamp` if for example you added the voucher as a token value.
> So let's say your `##form_upload##` token contains an upload voucher. The gateway could just replace that token
> and use the voucher to fetch from the bulky goods storage, right? Well, yes. While possible, it's still recommended
> you add the `BulkyItemsStamp` as this allows any gateway to differentiate whether the value of a token was really
> meant to be a bulky item or if - by chance - just has the same format as a voucher. Moreover, by providing the
> stamp, other extensions or your custom application code can read from the stamp and process those further if needed.
> In short: Always use the `BulkyItemsStamp`.

## Events

Sometimes, you need to add tokens to an already existing notification type or you want to log information etc.
That's when we have to dig a little deeper into the processes of the Notification Center.
There are four events you can use:

* AsynchronousReceiptEvent
* CreateParcelEvent
* GetNotificationTypeForModuleConfigEvent
* GetTokenDefinitionsForNotificationTypeEvent
* GetTokenDefinitionClassesForContextEvent
* ReceiptEvent

### AsynchronousReceiptEvent

This event is dispatched when a Gateway triggers `$notificationCenter->informAboutAsynchronousReceipt()` with the 
matching `AsynchronousReceipt`. You can use it to get informed about asynchronous parcel delivery updates, see 
[Asynchronous Gateways]({{% relref "gateways#asynchronous-gateways" %}}) for more information.

### CreateParcelEvent

This event is dispatched when a new `Parcel` is created within the Notification Center. If you create the `Parcel` instance
yourself using `new Parcel()`, you may call `$notificationCenter->dispatchCreateParcelEvent($parcel)` for that.

It allows to add stamps to the parcel or even replace it altogether. Notification Center itself uses this event to add
the `admin_email` token to all parcels for example. We're talking about the value here, the definition is added in the
`GetTokenDefinitionsEvent`.

### GetNotificationTypeForModuleConfigEvent

A typical use case will be to have a notification type selection in a front end module. For that, the Notification Center
ships with `tl_module.nc_notification` which is of `inputType` `select`. Depending on the front end module type, however,
developers will want to restrict the available notification options to a certain type. E.g. the `Lost password`
module shouldn't list notifications for a Contao form submission or an Isotope eCommerce status update type.

This event is here for you to be able to reuse `tl_module.nc_notification` in your palette and then filter for your
notification type.

### GetTokenDefinitionsForNotificationTypeEvent

This event is here to extend the list of token definitions for a given notification type. The Notification Center itself
uses this to add an `admin_email` token definition to all notification types for example. We're talking about the definition
here, the value is added in the `CreateParcelEvent`.

### GetTokenDefinitionClassesForContextEvent

This event is here to extend the list of token definition classes for a given context. The Notification Center itself
does not use this event. But if you introduced your own instance of `TokenDefinitionInterface` and you would want to make
sure this is shown e.g. in the `TokenContext::Email` context, this is your event!

### ReceiptEvent

This event is dispatched every time a `Parcel` was sent. It can be used to implement e.g. logging, retry logic etc.

[DI_Autoconfigure]: https://symfony.com/doc/current/service_container.html#the-autoconfigure-option