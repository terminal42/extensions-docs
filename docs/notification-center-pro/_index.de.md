---
title: "Notification Center Pro"
type: home
---

# Notification Center Pro

Notification Center Pro ist eine kommerzielle Erweiterung für unser [kostenloses und sehr beliebtes Notification Center][NC].

Zusätzlich zur kostenlosen Version erweitert Notification Center Pro dein Notification Center-Erlebnis um die
folgenden Funktionen:

## Versandbedingungen für Nachrichten

Mit [Bedingungen für Nachrichten]({{% ref "/message-conditions" %}}) kannst du ganze Nachrichten vom Versand ausschliessen und sie so bspw. abhängig von Formularfeldern verschicken lassen.

## Weitere, mitgelieferte Simple-Tokens

Formularfelder mit Optionen, wie bspw. Dropdowns oder Checkboxen, enthalten standardmässig die Rohwerte im jeweiligen Token.
Das [`formoptions_*`- Token]({{% ref "/additional-tokens" %}}) löst dieses Problem auf die bequemste Art und Weise für dich.

## Eigene Simple Tokens

Du kannst bequem deine [eigenen, benutzerdefinierten Simple Tokens]({{% ref "/custom-tokens" %}}) auf der Grundlage anderer Token erstellen. Dies erlaubt es dir viel flexibler zu sein, indem du Informationen aus anderen Token extrahieren, sie kombinieren oder praktisch alles damit tun kannst, was du mit Twig tun kannst.

## Logs

Die [Logs]({{% ref "/logs" %}}):

- erlauben es, alle über das Notification Center gesendeten Benachrichtigungen anzuzeigen
- werden für eine konfigurierbare Anzahl von Tagen aufbewahrt (standardmässig `7`)
- ermöglichen das erneute Versenden von Benachrichtigungen direkt vom Backend aus und erlauben sogar die Anpassung bestimmter Informationen, z.B. Simple Tokens, damit du Dinge einfach testen kannst
- bieten einen Diff-Viewer, um Unterschiede zwischen Log-Einträgen zu sehen, die auf der Grundlage eines anderen Log-Eintrags gesendet wurden

## Void Gateway

Das [Void Gateway]({{% ref "/void-gateway" %}}) sendet überhaupt keine Nachricht (`void` ist Englisch für "Nichts"). Stattdessen täuscht es nur eine erfolgreiche Zustellung vor, um das Testing zu vereinfachen.


[NC]: https://extensions.contao.org/?p=terminal42%2Fnotification_center