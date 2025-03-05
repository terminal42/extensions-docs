---
title: "CSV-Anhang"
url: "eigene-tokens/beispiele/csv-anhang"
---

Stell dir vor, du möchtest eine CSV-Datei an deine E-Mail anhängen, die die eingegebenen Formulardaten enthält.
Das Hauptproblem, das du dabei beachten musst, ist das korrekte Escaping. Stell dir eine Logik wie diese vor:

```
firstname,lastname,city
##form_firstname##,##form_firstname##,##form_city##
```

Das würde nicht funktionieren, indem du einfach die Tokens ersetzt, weil du drei verschiedene Zeichen escapen musst:

* Das Trennzeichen ("Delimiter" - `,` standardmässig)
* Das Begrenzungszeichen ("Enclosure" - `"` standardmässig)
* Das Escape-Zeichen selbst (`\` standardmässig)

Andernfalls, wenn ein Besucher z. B. `Jürgen "Kloppo"` als Vornamen und `Portland, ME` als Stadt angibt, um zu 
präzisieren, welches Portland gemeint ist, würdest du am Ende etwas erhalten, das völlig ungültiges CSV ist:

```
firstname,lastname,city
Jürgen "Kloppo",Klopp,Portland, ME
```

Die korrekte Ausgabe wäre:

```
firstname,lastname,city
"Jürgen \"Kloppo\"",Klopp,"Portland, ME"
```

Das manuell zu tun ist schwierig, deswegen verfügt das Notification Center Pro über einen `csv` Twig-Filter:

```twig
{% set headers = [
    'firstname', 
    'lastname',
    'city',
] %}
{{ headers|csv }}
{% set row = [
    parsedTokens.form_firstname|default(''), 
    parsedTokens.form_lastname|default(''), 
    parsedTokens.form_city|default(''), 
] %}
{{ row|csv }}
```

{{% notice tip %}}
Der [Twig `default`-Filter](https://twig.symfony.com/doc/3.x/filters/default.html) sorgt dafür, dass statt eines 
Fehlers eine leere Ausgabe erfolgt, wenn ein Token nicht existiert.
{{% /notice %}}

Das war's! Du hast nun ein Token mit dem korrekten CSV-Wert. Alles, was du noch tun musst, ist diesem Token einen 
Namen zu geben, es als Datei-Token zu aktivieren und ihm den gewünschten Dateinamen zu vergeben:

![Screenshot of the file attachment token configuration]({{% asset "/notification-center-pro/images/screenshot_csv_token_attachment.png" %}}?classes=shadow)

Dieses Token kann nun benutzt werden, um bspw. das CSV einer Mail anzuhängen. Tada! 🎉

{{% notice tip %}}
Der `csv`-Filter ist nicht Teil von Twig, sondern wird stattdessen vom Notification Center Pro bereitgestellt. Du 
kannst auch mit einem anderen Trennzeichen arbeiten. Wenn du die Werte beispielsweise mit `;` trennen möchtest, 
verwende `{{ row|csv(';') }}`. Der zweite und dritte Parameter können genutzt werden, um das "Enclosure" und 
Escape-Zeichen entsprechend anzupassen, falls erforderlich.
{{% /notice %}}