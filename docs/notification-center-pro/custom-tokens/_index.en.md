---
title: "Custom tokens"
---

With Notification Center Pro, you can create custom tokens which you can then use in your notifications. Custom 
tokens are based on Twig templates which means you can access all the goodness you know and love from Contao 
templating.

As the possibilities are virtually endless, let's look at an example. We might also create some kind of collection 
of popular examples. So if you have something you think other users could use, please let us know!

So imagine you have added a checkbox menu in the Contao form builder named `colors` and it would look like this:

![Eintrag der Richtlinie öffnen]({{% asset "/notification-center-pro/images/colors.png" %}}?width=200px&classes=shadow)

In the Notification Center, you will now receive the following token and associated value:

| Token name            | Value         |
|-----------------------|---------------|
| ##form_colors##       | green, yellow |

Now that's great if you want to write something like this in your message:

> Hello ##form_firstname##
> You have selected the following colors: ##form_colors##

However, what if you wanted to do something like this instead?

> Hello ##form_firstname##
> {if form_colors == "green"}
> You have selected green!
> {endif}

This is not possible because the value is not `green` but `green, yellow` instead. And because we have a multiple 
checkbox form field, the combinations could also be just `green` or `green, blue` or `green, blue, yellow` etc.

So what we would need is an additional token, so tha