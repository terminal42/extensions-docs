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
{% set header = [
    'firstname', 
    'lastname',
    'city',
] %}
{{ header|csv }}
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

### Alle Tokens ausgeben

Wenn du einfach gerne alle Tokens ausgegeben hättest, ist das denkbar einfach:

```twig
{{ parsedTokens|keys|csv }} {# Nimmt nur die Keys als Header #}
{{ parsedTokens|csv }} {# Nimmt nur die Werte als Reihe #}
```

### Komplexere Aufgaben

Natürlich kannst du auch deutlich komplexere Aufgaben erfüllen. Wenn du bspw. nur die Formulardaten mit dem 
passenden Formular-Token haben willst, dann bist du erstmal nur an allen `form_*` Tokens interessiert und willst 
diese mit dem passenden `formlabel_*` ausstatten. Oder vielleicht willst du auch noch die Formulardaten anpassen und 
Dinge berechnen oder ähnliches. Hier ein ausführliches Beispiel mit Kommentaren - kopiere dir einfach die Sachen 
zusammen, die du brauchst!

```twig
{# Definieren wir zunächst zwei Arrays für die Kopfzeile und die Werte-Zeile#}
{% set header = [] %}
{% set row = [] %}

{% for token in rawTokens %}
    {# So kannst du die Token filtern, die "form_" in ihrem Namen haben #}
     {% if 'form_' in token.name %}
     
        {% set headerValue = token.name %} {# So nimmst du den Token-Namen als Header #}
        {% set headerValue = token.name|replace({'form_': ''}) %} {# oder möchtest du das "form_" aus dem Namen entfernen? #}
    
        {# oder möchtest du das "form_" aus dem Token entfernen und den passenden Token-Wert aus "formlabel_*" nehmen? #}
        {% set labelTokenName = token.name|replace({'form_': 'formlabel_'}) %}
        {% if rawTokens.has(labelTokenName) %}
            {% set headerValue = rawTokens.byName(labelTokenName).parserValue %} 
        {% else %}
            {% set headerValue = token.parserValue %} {# wir behalten den ursprünglichen Token-Namen, wenn kein passendes „formlabel_*“-Token gefunden wurde (sollte nicht passieren) #}
        {% endif %}
        
        {% set header = header|merge([headerValue]) %} {# unseren transformierten Header-Wert als Header verwenden #}


        {# unsere spezielle Transformation nur auf das Token "form_firstname" anwenden #}
        {% if token.name is same as 'form_firstname' %}
            {% set rowValue = token.value|upper %} {# nach Belieben transformieren, indem du Twig-Filter und -Funktionen auf den Rohwert anwendest (kann ein Array sein) #}
        {% else %}
            {% set rowValue = token.parserValue %} {# den ursprünglichen Parserwert beibehalten (ist immer ein String) #}
        {% endif %}
        {% set row = row|merge([rowValue]) %} {# unseren transformierten Wert als Zeilenwert verwenden #}
        
        
     {% endif %}

{% endfor %}
{# Jetzt müssen wir nur noch die Arrays ausgeben und sie als CSV formatieren #}
{{ header|csv }}
{{ row|csv }}
```

{{% notice tip %}}
Der `csv`-Filter ist nicht Teil von Twig, sondern wird stattdessen vom Notification Center Pro bereitgestellt. Du
kannst auch mit einem anderen Trennzeichen arbeiten. Wenn du die Werte beispielsweise mit `;` trennen möchtest,
verwende `{{ row|csv(';') }}`. Der zweite und dritte Parameter können genutzt werden, um das "Enclosure" und
Escape-Zeichen entsprechend anzupassen, falls erforderlich.
{{% /notice %}}