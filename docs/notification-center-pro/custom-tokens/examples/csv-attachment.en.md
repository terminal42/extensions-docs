---
title: "CSV attachment"
---

Imagine you wanted to attach a CSV file to your e-mail that contains the form data that was submitted.
The major problem here you need to consider is correct escaping. Imagine a logic like this:

```
firstname,lastname,city
##form_firstname##,##form_firstname##,##form_city##
```

This would not work by simply replacing the tokens because you need to escape 3 different characters:

* The separator (`,` by default)
* The enclosure (`"` by default)
* The escape character itself (`\` by default)

Otherwise, if a visitor e.g. submits `Jürgen "Kloppo"` as their firstname and `Portland, ME` as city to clarify which 
Portland, you would end up having this which is completely invalid CSV:

```
firstname,lastname,city
Jürgen "Kloppo",Klopp,Portland, ME
```

The correct output would be

```
firstname,lastname,city
"Jürgen \"Kloppo\"",Klopp,"Portland, ME"
```

Doing that manually is hard, this is why the Notification Center Pro provides its own `csv` Twig filter:

```twig
{% set headers = [
    'firstname', 
    'lastname',
    'city',
] %}
{{ headers|csv }}
{% set row = [
    parsedTokens.form_firstname|default(''), 
    parsedTokens.form_lastname|default(''), 
    parsedTokens.form_city|default(''), 
] %}
{{ row|csv }}
```

{{% notice tip %}}
The [Twig `default` filter](https://twig.symfony.com/doc/3.x/filters/default.html) would make sure there is an empty 
value if the token is not provided instead of throwing an error.
{{% /notice %}}

That's it, you now have a token with the correct CSV value. All you need to do is to give this token a name, enable 
it as a file token and give it your desired filename:

![Screenshot of the file attachment token configuration]({{% asset "/notification-center-pro/images/screenshot_csv_token_attachment.png" %}}?classes=shadow)

This token can now be used to e.g. attach it to an e-mail. Tada! 🎉

{{% notice tip %}}
The `csv` filter is not part of Twig but instead provided by the Notification Center Pro. You can also work with a 
different delimiter. If you need to separate the values by `;` for example, use `{{ row|csv(';') }}`. The second and 
third argument can be used to configure the enclosure and escape character respectively, if required.
{{% /notice %}}