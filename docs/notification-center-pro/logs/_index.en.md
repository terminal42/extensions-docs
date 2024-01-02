---
title: "Logs"
weight: 3
---

Notification Center Pro automatically logs all the notifications that have been sent. Usually, this is already very 
helpful in itself but the Notification Center Pro comes with additional features on top of that!

## Resend Cockpit

You can resend any log entry. To access the Resend Cockpit, click on the respective operation for the log entry:

![Screenshot of the resend operation]({{% asset "/notification-center-pro/images/screenshot_resend.png"%}}?classes=shadow)

You will now be able to resend this notification. You can do this either entirely unchanged or enforce reloading certain information.

![Screenshot of the Resend Cockpit]({{% asset "/notification-center-pro/images/screenshot_resend_cockpit.png"%}}?classes=shadow)

By default, all messages will be sent again completely unchanged. So, for example, if you have changed the configuration of a gateway in the meantime, this will not affect this message. You may adjust this behavior to your needs and overwrite certain information in the Cockpit.

{{% notice tip %}}
Note that the content of the message may contain information that has since become invalid. Examples of this would include expired activation links (opt-in tokens). These cannot be not be regenerated.
{{% /notice %}}

## Diff view

In case you did resend a notification, Notification Center Pro will know that it was resent based on another notification. Thus, it can offer you to open a diff view, so you can easily compare the two log entries.

To access the diff view, click on the respective operation:

![Screenshot of the diff view operation]({{% asset "/notification-center-pro/images/screenshot_resend_diff.png"%}}?classes=shadow)

The diff view will show you the entire difference between the two sent "parcels". Meaning that you will see the difference between all the metadata (stamps) and not only the tokens. Accordingly, all diff views will have a different `ResentStamp` because that's the metadata for Notification Center Pro to know which was the source log entry for this new notification.

A possible view could be this then:

![Screenshot of the diff view]({{% asset "/notification-center-pro/images/screenshot_resend_diff_view.png"%}}?classes=shadow)

## Configuring log retention period

By default, logs are kept for `7` days, but you can adjust this in your `config/config.yaml` of your Contao Setup 
like so:

```yaml
terminal42_notification_center_pro:
    logs:
        retention_period: 14
```

{{% notice info %}}
Make sure to rebuild the Cache using the Contao Manager or the `cache:clear` command.
{{% /notice %}}