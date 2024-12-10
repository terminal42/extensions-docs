---
title: "Webhooks"
url: "webhooks"
weight: 200
---

Durch die Einrichtung von Webhooks kann der Shop auf Ereignisse in deinem Stripe-Konto reagieren,
um die korrekte Verarbeitung von Zahlungen sicherzustellen. Weitere Erklärungen findest 
du in der [Dokumentation von Stripe](https://docs.stripe.com/webhooks).

{{% notice info %}}
**Für die Nutzung von Webhooks benötigtst du mindestens Version 1.0.0 der Erweiterung.**  
Beachte das Webhooks nicht auf lokalen Testsystemen funktionieren, oder wenn du
deinen Shop mittels Passwort geschützt hast.
{{% /notice %}}


## Einrichtung bei Stripe

Registriere die URL des Webhook-Endpoints im Abschnitt [Webhooks](https://dashboard.stripe.com/webhooks)
im Entwickler-Dashboard. Dadurch weiss Stripe, wohin Ereignisse übermittelt werden sollen.

Als _Event_ benötigt Isotope nur **checkout.session.completed**. Die **Endpoint-URL** findest du im Backend von Contao, wenn du die entsprechende Zahlungsmethode bearbeitest.
Sie wird oben als Hinweis über dem Eingabeformular angezeigt.


## Einrichtung bei Isotope

Für die Verarbeitung von Webhooks ist bei Isotope keine Anpassung nötig.
Sobald die entsprechenden Meldungen von Stripe eingehen, werden diese
vom Shop automatisch akzeptiert und falls nötig verarbeitet.
