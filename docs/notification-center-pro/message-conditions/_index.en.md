---
title: "Conditions for messages"
weight: 100
---

With conditions for messages, you can define conditions in addition to the standard publication fields that must be met for a message to be sent.

## Example

You want to send all emails to the sales department. Urgent inquiries should **in addtion** be sent to a special team.
The customer himself decides in the form whether it is an urgent inquiry.

Then let's create the checkbox in our form:

![Screenshot of the form generator in the back end. We add a checkbox called "urgent". The value, if checked, is "yes".]({{% asset "/notification-center-pro/images/screenshot_message_condition_form_be.png" %}}?classes=shadow)

In the front end, it looks something like this for our customers:

![Screenshot of the front end with our checkbox]({{% asset "/notification-center-pro/images/screenshot_message_condition_form_fe.png" %}}?height=200px&classes=shadow)

Now we can use `##form_urgent## === 'yes'` to send an entire message only if the customer has ticked this checkbox:

![Screenshot of the condition in the back end]({{% asset "/notification-center-pro/images/screenshot_message_condition.png" %}}?classes=shadow)

{{% notice tip %}}
This function is particularly powerful in combination with [custom tokens]({{< ref "/custom-tokens" >}})!
{{% /notice %}}
