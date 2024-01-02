---
title: "Notification Center Pro"
---

# Notification Center Pro

Notification Center Pro ist eine kommerzielle Erweiterung für unser [kostenloses und sehr beliebtes Notification Center][NC].

Zusätzlich zur kostenlosen Version erweitert Notification Center Pro dein Notification Center-Erlebnis um die
folgenden Funktionen:

## Void Gateway

Das [Void Gateway](./void-gateway) sendet überhaupt keine Nachricht (`void` is Englisch für "Nichts"). Stattdessen täuscht es nur eine erfolgreiche Zustellung vor, um das Testing zu vereinfachen.

## Logs

Die [Logs](./logs):

- erlauben es, alle über das Notification Center gesendeten Benachrichtigungen anzuzeigen
- werden für eine konfigurierbare Anzahl von Tagen aufbewahrt (standardmässig `7`)
- ermöglichen das erneute Versenden von Benachrichtigungen direkt vom Backend aus und erlauben sogar die Anpassung bestimmter Informationen, z.B. Simple Tokens, damit du Dinge einfach testen kannst
- bieten einen Diff-Viewer, um Unterschiede zwischen Log-Einträgen zu sehen, die auf der Grundlage eines anderen Log-Eintrags gesendet wurden

## Eigene Simple Tokens

Du kannst bequem deine [eigenen, benutzerdefinierten Simple Tokens](./custom-tokens) auf der Grundlage anderer Token erstellen. Dies erlaubt es dir viel flexibler zu sein, indem du Informationen aus anderen Token extrahieren, sie kombinieren oder praktisch alles damit tun kannst, was du mit Twig tun kannst.

[NC]: https://extensions.contao.org/?p=terminal42%2Fnotification_center