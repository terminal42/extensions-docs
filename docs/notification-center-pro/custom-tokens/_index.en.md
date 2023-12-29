---
title: "Custom tokens"
---

With Notification Center Pro, you can create custom tokens which you can then use in your notifications. Custom 
tokens are based on Twig templates which means you can access all the goodness you know and love from Contao 
templating.

As the possibilities are virtually endless, let's look at an example. We might also create some kind of collection 
of popular examples. So if you have something you think other users could use, please let us know!

## Example use case

So imagine you have added a checkbox menu in the Contao form builder named `colors` and it would look like this:

![Screenshot of a form field with multiple colors]({{% asset "/notification-center-pro/images/colors.png" %}}?width=200px&classes=shadow)

In the Notification Center, you will now receive the following token and associated value:

| Token name            | Value         |
|-----------------------|---------------|
| ##form_colors##       | green, yellow |

Now that's great if you want to write something like this in your message:

> Hello ##form_firstname## \
> You have selected the following colors: ##form_colors##

However, what if you wanted to do something like this instead?

> Hello ##form_firstname## \
> {if form_colors == "green"} \
> You have selected green! \
> {endif}

This is not possible because the value is not `green` but `green, yellow` instead. And because we have a multiple 
checkbox form field, the combinations could also be just `green` or `green, blue` or `green, blue, yellow` etc.

So what we would need is an additional token, so that we end up having two tokens in our notification:


| Token name         | Value         |
|--------------------|---------------|
| ##form_colors##    | green, yellow |
| ##is_color_green## | yes           |

Because then, in our notification we can achieve our goal and write this:

> Hello ##form_firstname## \
> {if is_color_green == "yes"} \
> You have selected green! \
> {endif}

## Adding the custom token in the back end

![Screenshot of the back end when adding a new token]({{% asset"/notification-center-pro/images/screenshot_is_color_green.png" %}}?classes=shadow)

Let's quickly go through the settings of the screenshot here.

1. Title
   Here you can give your token an internal title, this is only used for the list in the back end.
2. Token name
   Here you define the name of your token which you will then have available in your notifications. We use 
   `is_color_green` but you can use anything here. Just make sure it does not conflict with other tokens. For 
   example, if you are working in the `Contao form generator submission` type, do not name your token 
   `form_is_color_green` because that might lead to a conflict.
3. Notification type
   For exactly the conflict reason, you have to assign your token to a notification type. Depending on the 
   notification type you will have different tokens available and thus it does not make any sense to assign a token 
   to all notification types. Choose the one you want to work with here.
4. Type
   Here you can choose whether you want to write your Twig template for just this very token or select from a 
   template on your filesystem. Note that when you create a template, make sure they reside 
   inside `templates/notification_center_pro/custom_token/` - so in this case you could for 
   example choose `templates/notification_center_pro/custom_token/is_color_green.html.twig`.
5. Write your Twig template
   In our example, however, we entered the template directly for this token only.

Here is the Twig code visible in the screenshot and annotated with comments for you to understand:

```twig
{#
    We want to make sure, there is no white space but just our token value. You can control the whitespace handling
    in Twig to achieve exactly what you want. See https://twig.symfony.com/doc/3.x/templates.html#templates-whitespace-control
    for more information.
#}
{% apply spaceless %}

{#
    First of all, it's always important to wrap the whole template into an if statement in order to check whether
    there even was a ##form_colors## token provided. The easiest way is to use the "has()" method on our rawTokens.
#}
{% if rawTokens.has('form_colors') %}

{#
    Here is an example how you could split a string by ", " if it was in the format of "green, red, blue".
#}
{% set colors = parsedTokens.form_colors|split(', ') %}

{#
    We now defined a variable in this template named "colors" which now contains an array. So we can achieve our
    desired token content by checking if "green" is part of that array now and output "yes" or "no" respectively.
#}
{% if 'green' in colors %}yes{% else %}no{% endif %}

{% endif %}
{% endapply %}
```

That's it, you now have a token `is_color_green` which contains either `yes` or `no`. Congratulations! 🎉
