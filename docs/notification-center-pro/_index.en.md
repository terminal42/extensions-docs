---
title: "Notification Center Pro"
---

# Notification Center Pro

Notification Center Pro is a commercial extension to our [free and very popular Notification Center][NC]. 

On top of using the free version, Notification Center Pro will enhance your Notification Center experience with the 
following features:

## Void gateway

This gateway does not send any message at all. Instead, it just fakes a successful delivery allowing
for easier testing.

## Logs

- Allows to view all notifications sent via the Notification Center
- Logs are kept for a configurable amount of days (`7` by default)
- Allows to re-send notifications right from the logs and even allows to adjust certain information e.g.
  Simple Tokens, so you can test things easily
- Provides a diff viewer to see differences between log entries being sent based on another one

## Custom Simple Tokens

You can conveniently create your [own, custom Simple Tokens](./custom-tokens) based on other tokens. This will allow you to be a 
lot more flexible by extracting partial information from other tokens, combining them or virtually doing whatever you can do
with Twig with them.






[NC]: https://extensions.contao.org/?p=terminal42%2Fnotification_center