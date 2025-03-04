---
title: "Bedingungen für Nachrichten"
weight: 100
---

Mit eigenen Bedingungen für Nachrichten kannst du zusätzlich zu den Standardfeldern Bedingungen definieren, die für einen Versand erfüllt sein müssen.

## Beispiel

Du möchtest grundsätzlich alle E-Mails in die Verkaufsabteilung schicken. Dringende Anfragen sollen **zusätzlich** an ein spezielles Team geschickt werden.
Ob es sich um eine dringende Anfrage handelt, entscheidet der Kunde selber im Formular.

Dann lasst uns mal die Checkbox in unserem Formular anlegen:

![Screenshot des Formulargenerators im Backend. Wir fügen eine Checkbox namens "urgent" hinzu. Der Wert, wenn angehakt lautet "yes".]({{% asset "/notification-center-pro/images/screenshot_message_condition_form_be.png" %}}?classes=shadow)

Im Frontend sieht das dann ungefähr so aus für unsere Kunden:

![Screenshot des Frontends mit unserer Checkbox]({{% asset "/notification-center-pro/images/screenshot_message_condition_form_fe.png" %}}?height=200px&classes=shadow)

Nun können wir mit `##form_urgent## === 'yes'` eine gesamte Nachricht nur dann versenden lassen, wenn der Kunde diese Checkbox angehakt hat:

![Screenshot der Bedingung im Backend]({{% asset "/notification-center-pro/images/screenshot_message_condition.png" %}}?classes=shadow)

{{% notice idea %}}
Technisch gesehen brauchst du die `##` nicht, wenn du Bedingungen definierst. Sie werden entfernt, bevor sie ausgewertet werden. Ihre Verwendung
dient nur zu deiner eigenen Benutzerfreundlichkeit, da sie die automatische Vervollständigung aktivieren. Du kannst aber genauso gut direkt `form_urgent === 'yes'` schreiben. Beide
Varianten werden funktionieren. Bedingungen innerhalb des E-Mail-Textes z.B. werden ja auch ohne die `##` geschrieben (z.B. `{if form_urgent === 'yes'}`).
{{% /notice %}}

{{% notice tip %}}
Diese Funktion ist besonders mächtig im Zusammenspiel mit [benutzerdefinierten Tokens]({{% ref "/custom-tokens" %}})!
{{% /notice %}}
