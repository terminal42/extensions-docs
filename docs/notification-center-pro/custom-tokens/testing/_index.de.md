---
title: "Eigene Tokens testen"
url: "eigene-tokens/testen"
weight: 10
---

Das Testen deiner eigenen Tokens kann auf viele Arten erfolgen. Die naheliegendste Möglichkeit ist, deine Benachrichtigung
immer wieder zu senden, bis du das gewünschte Ergebnis bekommst. Dies erscheint jedoch sehr mühsam, weshalb das Notification Center Pro mit einem Testmodus ausgestattet ist:

![Screenshot des Test-Buttons]({{% asset "/notification-center-pro/images/screenshot_test_mode.png" %}}?classes=shadow)

Eigene Tokens sind immer zusätzlich zu einer Sammlung an Tokens ("token collection"), die du nicht beeinflussen kannst. Ein Beispiel:
Der Benachrichtigungstyp `Contao Formulargenerator-Übertragungen` stellt alle Formularfelder sowie die Konfiguration des Formulars
selbst als Tokens zur Verfügung. Was du nun mit eigenen Tokens machen kannst, ist das **Hinzufügen** von weiteren Tokens, die auf diesen existierenden Tokens basieren.
Zum Testen musst du also dem Notification Center Pro eine Token-Sammlung zur Verfügung stellen, mit der es arbeiten kann.

Wie du auf dem Screenshot sehen kannst, gibt es zwei Testmodi:

* Testen auf der Grundlage deiner eigenen Token-Sammlung. \
  Dies kann für sehr einfache Anwendungsfälle sehr praktisch sein. Wenn dein Formular zum Beispiel sehr kurz ist und du genau weisst
  welche Werte bereitgestellt werden, ist dies eine sehr schnelle Möglichkeit, Tokens zu testen.
* Testen auf der Grundlage eines Log-Eintrags. \
  Stell dir vor, du hast ein Formular (oder einen anderen Benachrichtigungstypen) mit vielen Tokens. Vielleicht ein Formular mit 30 Formularfeldern. Es wäre sehr mühsam, diese Test-Tokens manuell zu erstellen. Anstatt das zu tun, kannst du dein
  eigenes Token auch auf der Grundlage eines Log-Eintrags testen.

## Das Void Gateway nutzen

Verwende das vom Notification Center Pro bereitgestellte `Void Gateway`, um einen Log-Eintrag zu erzeugen, den du dann zum Testen deiner eigenen Tokens verwenden kannst. Das `Void Gateway` verhält sich genau wie jedes andere Gateway, aber es sendet die Benachrichtigung nicht wirklich. Es erzeugt jedoch einen regulären Log-Eintrag, als ob die Benachrichtigung gesendet worden wäre. Auf diese Weise kannst du sehr einfach einige Testfälle erstellen, auf deren Grundlage du dann deine eigenen Tokens erstellen und testen kannst.

## Debugging

{{% notice warning %}}
Nachfolgende Beschreibung funktioniert nur, wenn du dich im **Debug-Modus von Contao** befindest!
{{% /notice %}}

Verwende `{{ dump() }}` in deinem Twig-Template. Du kannst dann sowohl die `parsedTokens` als auch die `rawTokens` bequem
betrachten:

![Screenshot des Dump-Outputs]({{% asset "/notification-center-pro/images/screenshot_dump.png" %}}?classes=shadow)
