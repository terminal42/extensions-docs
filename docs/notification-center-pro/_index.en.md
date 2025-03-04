---
title: "Notification Center Pro"
type: home
---

# Notification Center Pro

Notification Center Pro is a commercial extension to our [free and very popular Notification Center][NC]. 

On top of using the free version, Notification Center Pro will enhance your Notification Center experience with the 
following features:

## Dispatch conditions for messages

With [conditions for messages]({{% ref "/message-conditions" %}}) you can exclude entire messages from being sent and thus have them sent depending on form fields, for example.

## More form tokens

Form fields with options, such as dropdowns or checkboxes, contain the raw values in the respective tokens by default.
The [`formoptions_*`- token]({{% ref "/additional-tokens" %}}) solves this problem in the most convenient way for you.

## Custom Simple Tokens

You can conveniently create your [own, custom Simple Tokens]({{% ref "/custom-tokens" %}}) based on other tokens. This will allow you to be a
lot more flexible by extracting partial information from other tokens, combining them or virtually doing whatever you can do
with Twig with them.

## Logs

The [Logs]({{% ref "/logs" %}}):

- allow to view all notifications sent via the Notification Center
- are kept for a configurable amount of days (`7` by default)
- allow to re-send notifications right from the back end and even allow to adjust certain information e.g.
  Simple Tokens, so you can test things easily
- provides a diff viewer to see differences between log entries being sent based on another one

## Void Gateway

The [Void Gateway]({{% ref "/void-gateway" %}}) does not send any message at all. Instead, it just fakes a successful delivery allowing
for easier testing.

[NC]: https://extensions.contao.org/?p=terminal42%2Fnotification_center