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
{% set header = [
    'firstname', 
    'lastname',
    'city',
] %}
{{ header|csv }}
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

### Output all tokens

If you simply want to output all tokens, this is very easy:

```twig
{{ parsedTokens|keys|csv }} {# Takes only the keys as header #}
{{ parsedTokens|csv }} {# Takes only the values as row #}
````

### More complex tasks

Of course, you can also perform much more complex tasks. For example, if you only want to have the form data with the
matching form token, then you are only interested in all `form_*` tokens and want to equip them
with the matching `formlabel_*`. Or maybe you also want to customize the form data and calculate
values etc. Here is a detailed example with comments - just copy and paste the things
that you need!

```twig
{# First, let's define two arrays for the header and the row so we can add values to them #}
{% set header = [] %}
{% set row = [] %}

{% for token in rawTokens %}
    {# This is how you can filter those tokens that have "form_" in their name #}
     {% if 'form_' in token.name %}
     
        {% set headerValue = token.name %} {# use our token name as header #}
        {% set headerValue = token.name|replace({'form_': ''}) %} {# or would you like to strip the "form_" of the token? #}
    
        {# or would you like to strip the "form_" of the token and take the matching "formlabel_*" token value? #}
        {% set labelTokenName = token.name|replace({'form_': 'formlabel_'}) %}
        {% if rawTokens.has(labelTokenName) %}
            {% set headerValue = rawTokens.byName(labelTokenName).parserValue %} 
        {% else %}
            {% set headerValue = token.name %} {# keep original token name if no matching "formlabel_*" token was found 
            (should not happen) #}
        {% endif %}
        
        {% set header = header|merge([headerValue]) %} {# use our transformed header value as header #}


        {# apply our special transformation only to the "form_firstname" token #}
        {% if token.name is same as 'form_firstname' %}
            {% set rowValue = token.value|upper %} {# transform as you like using Twig filters and functions on the raw value (might be an array) #}
        {% else %}
            {% set rowValue = token.parserValue %} {# keep original parser value (always a string) #}
        {% endif %}
        {% set row = row|merge([rowValue]) %} {# use our transformed value as row value #}
        
        
     {% endif %}

{% endfor %}
{# And now, all we need to do is output the arrays and format them as CSV #}
{{ header|csv }}
{{ row|csv }}
```

{{% notice tip %}}
The `csv` filter is not part of Twig but instead provided by the Notification Center Pro. You can also work with a 
different delimiter. If you need to separate the values by `;` for example, use `{{ row|csv(';') }}`. The second and 
third argument can be used to configure the enclosure and escape character respectively, if required.
{{% /notice %}}