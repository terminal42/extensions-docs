---
title: "Logs"
weight: 3
---

Das Notification Center Pro protokolliert automatisch alle Benachrichtigungen, die gesendet wurden. Normalerweise ist das
an sich schon sehr hilfreich, aber das Notification Center Pro bietet darüber hinaus noch weitere Funktionen!

## Erneut senden-Cockpit

Du kannst jeden Log-Eintrag erneut senden. Um das "Erneut senden-Cockpit" aufzurufen, klicke auf die entsprechende Operation für den Log-Eintrag:

![Screenshot der erneut-senden-Operation]({{% asset "/notification-center-pro/images/screenshot_resend.png"%}}?classes=shadow)

Du kannst diese Nachricht nun erneut versenden. Du kannst dies entweder völlig unverändert tun oder das Nachladen bestimmter Informationen erzwingen.

![Screenshot des Cockpits]({{% asset "/notification-center-pro/images/screenshot_resend_cockpit.png"%}}?classes=shadow)

Standardmässig werden alle Nachrichten völlig unverändert erneut gesendet. Wenn du also z.B. die Konfiguration eines Gateways zwischenzeitlich geändert hast, hat dies keine Auswirkungen auf diese Meldung. Du kannst dieses Verhalten an deine Bedürfnisse anpassen und bestimmte Informationen im Cockpit überschreiben lassen.

{{% notice tip %}}
Beachte, dass der Inhalt der Nachricht Informationen enthalten kann, die inzwischen ungültig geworden sind. Beispiele hierfür sind abgelaufene Aktivierungslinks (Opt-in-Token). Diese können nicht neu generiert werden.
{{% /notice %}}

## Diff-Ansicht

Falls du eine Benachrichtigung erneut gesendet hast, weiss das Notification Center Pro, dass diese aufgrund einer anderen Benachrichtigung erneut gesendet wurde. Daher kann es dir anbieten, eine Diff-Ansicht zu öffnen, so dass du die beiden Log-Einträge leicht vergleichen können.

Klicke auf den entsprechenden Button, um die Diff-Ansicht aufzurufen:

![Screenshot des Diff-Ansicht Buttons]({{% asset "/notification-center-pro/images/screenshot_resend_diff.png"%}}?classes=shadow)

Die Diff-Ansicht zeigt dir den gesamten Unterschied zwischen den beiden gesendeten "Paketen". Das bedeutet, dass du den Unterschied zwischen allen Metadaten (Stamps) und nicht nur den Token sehen wirst. Dementsprechend haben alle Diff-Ansichten einen anderen `ResentStamp`, weil dies die Metadaten sind, anhand derer das Notification Center Pro weiss, welcher Log-Eintrag die Quelle für diese neue Benachrichtigung war.

So könnte das dann aussehen: 

![Screenshot der Diff-Ansicht]({{% asset "/notification-center-pro/images/screenshot_resend_diff_view.png"%}}?classes=shadow)

## Aufbewahrungsdauer der Logs konfigurieren

Standardmässig werden die Logs `7` Tage aufbewahrt, aber du kannst dies in deiner `config/config.yaml` deines Contao Setups wie folgt anpassen:

```yaml
terminal42_notification_center_pro:
    logs:
        retention_period: 14
```

{{% notice info %}}
Stelle sicher, dass du den Cache mit dem Contao Manager oder dem Befehl `cache:clear` neu aufbaust.
{{% /notice %}}