---
title: "Calculating with Twig"
---

Do you know this situation? A simple order form, without a large webshop implementation, but you would still like to 
have the VAT and the total shown in the e-mail? No problem at all with the Notification Center Pro, we can create our own tokens for the VAT and the total and then use them. Let's assume we have a form field called `number_of_brochures` where the customer can brochures. Each brochure costs 4 Euros. If someone now orders 5 brochures, we would like to send a mail with 20 Euro plus 3.80 Euro VAT (19% for Germany in this example) and a total amount of 23.80 Euro.

For example, we write the following in our mail:

```none
Hello ##form_firstname##,

You have ordered ##form_number_of_brochures## brochures from us. Here is your invoice:

##form_number_of_brochures## brochures at € 4,- each: ##subtotal##
Value added tax 19%: ##tax##
Total amount: ##total##
```

So könnte unsere Twig-Logik könnte dann wie folgt aussehen:

```twig
{% if rawTokens.has('form_anzahl_prospekte') %}
{% set subtotal = rawTokens.byName('form_number_of_brochures').value * 4 %}
{% set tax = subtotal * 19 / 100 %}
{% set total = subtotal + tax %}
{% endif %}
```

We now copy this logic into 3 tokens and output the desired variable in each case:

1. token `subtotal` uses the above logic and `{{ subtotal }}`
2. token `tax` uses the above logic and `{{ tax }}`
3. token `total` uses the above logic and `{{ total }}`

Tada! 🎉

{{% notice tip %}}
Use the [Twig `number_format` filter](https://twig.symfony.com/doc/3.x/filters/number_format.html) to format the numbers to your liking!
{{% /notice %}}




