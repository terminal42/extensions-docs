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

Um die Autovervollständigung von SimpleToken nutzen zu können, werden die Feldnamen wie gewohnt mit `##form_feldname##` eingegeben. Die `##-Zeichen` werden dann beim Speichern automatisch entfernt.

{{% notice tip %}}
Diese Funktion ist besonders mächtig im Zusammenspiel mit [benutzerdefinierten Tokens]({{< ref "/custom-tokens" >}})!
{{% /notice %}}
