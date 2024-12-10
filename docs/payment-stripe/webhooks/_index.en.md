---
title: "Webhooks"
url: "webhooks"
weight: 200
---

By setting up webhooks, your shop can react to events in your Stripe account
to ensure the correct processing of payments. Further explanations can be found
in the [Stripe documentation](https://docs.stripe.com/webhooks).

{{% notice info %}}
**To use webhooks you need at least version 1.0.0 of the extension.**
Note that webhooks do not work on local test systems, or if you have protected
your store website with a password.
{{% /notice %}}


## Setting up Stripe

Register the URL of the webhook endpoint in the [Webhooks](https://dashboard.stripe.com/webhooks) section
in the developer dashboard. This tells Stripe where events should be transmitted to.

As _event_ Isotope only needs **checkout.session.completed**. You can find the **Endpoint URL** in the Contao backend 
when you edit the corresponding payment method. It is displayed as a note above the input form.


## Setting up Isotope

Isotope does not require any setup for processing webhooks.
As soon as the corresponding messages are received from Stripe, they are
automatically accepted by the store and processed if necessary.
