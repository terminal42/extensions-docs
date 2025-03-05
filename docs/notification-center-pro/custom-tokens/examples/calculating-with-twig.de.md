---
title: "Rechnen mit Twig"
url: "eigene-tokens/beispiele/rechnen-mit-twig"
---

Kennst du das? Ein einfaches Bestellformular, ohne grosse Webshop-Implementierung aber trotzdem hättest du gerne die 
MwSt. und das Total in der E-Mail ausgewiesen? Mit dem Notification Center Pro absolut kein Problem, wir können uns 
ja eigene Tokens für die MwSt. und das Total erstellen und diese dann nutzen. Wir nehmen an, wir haben ein Formularfeld, das heisst `anzahl_prospekte` und dort kann man als Besteller:in Prospekte bestellen. Jeder Prospekt kostet 4 Euro. Wenn jetzt jemand 5 Prospekte bestellt, möchten wir also eine Mail mit 20 Euro plus 3.80 Euro MwSt (19% in diesem Beispiel) und einem Gesamt-Total von 23.80 Euro schicken.

In unsere Mail schreiben wir bspw. Folgendes:

```none
Hallo ##form_firstname##,

Du hast bei uns ##form_anzahl_prospekte## Prospekte bestellt. Hier ist deine Rechnung:

##form_anzahl_prospekte## Prospekte à € 4,-: ##subtotal##
Mehrwertsteuer 19%: ##mwst##
Gesamttotal: ##gesamttotal##
```

So könnte unsere Twig-Logik dann aussehen:

```twig
{% if rawTokens.has('form_anzahl_prospekte') %}
{% set subtotal = rawTokens.byName('form_anzahl_prospekte').value * 4 %}
{% set mwst = subtotal * 19 / 100 %}
{% set gesamttotal = subtotal + mwst %}
{% endif %}
```

Diese Logik kopieren wir nun in 3 Tokens und geben jeweils die gewünschte Variable aus:

1. Token `subtotal` nutzt obengenannte Logik und `{{ subtotal }}`
2. Token `mwst` nutzt obengenannte Logik und `{{ mwst }}`
3. Token `gesamttotal` nutzt obengenannte Logik und `{{ gesamttotal }}`

Tada! 🎉

{{% notice tip %}}
Verwende den [Twig-Filter `number_format`](https://twig.symfony.com/doc/3.x/filters/number_format.html), um die Zahlen nach deinen Wünschen zu formatieren!
{{% /notice %}}
