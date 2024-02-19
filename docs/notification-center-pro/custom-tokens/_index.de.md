---
title: "Eigene Tokens"
url: "eigene-tokens"
weight: 200
---

Mit dem Notification Center Pro kannst du benutzerdefinierte Tokens erstellen, die du dann in deinen Benachrichtigungen verwenden kannst. Eigene Tokens basieren auf Twig-Template, d.h. du kannst von allen Twig-Funktionen profitieren, die du dir vom Contao-Templating gewohnt bist.

Da die Möglichkeiten praktisch endlos sind, sehen wir uns ein Beispiel an. Du kannst auch einen Blick auf unsere [Beispielsammlung](./example-collection) werfen, in der wir gängige Anwendungsfälle sammeln. Wenn du etwas hast, von dem du denkst, dass andere es verwenden könnten, lass es uns bitte wissen!

## Beispiel Anwendungsfall

Stell dir sich vor, du hast im Contao-Formulargenerator ein Checkbox-Menü mit dem Namen `colors` hinzugefügt, das wie folgt aussehen würde:

![Screenshot eines Formularfelds mit Farbauswahl]({{% asset "/notification-center-pro/images/colors.png" %}}?width=200px&classes=shadow)

Im Notification Center kriegst du nun das folgende Token und den dazugehörigen Wert:

| Token name            | Value         |
|-----------------------|---------------|
| ##form_colors##       | green, yellow |

Das ist grossartig, wenn du etwas in der Art in deiner Nachricht schreiben möchtest:

> Hallo ##form_firstname## \
> Sie haben die folgenden Farben ausgewählt: ##form_colors##

Aber was wäre, wenn du stattdessen etwas wie das hier machen möchstest?

> Hallo ##form_firstname## \
> {if form_colors == "green"} \
> Sie haben grün ausgewählt! \
> {endif}

Dies ist nicht möglich, weil der Wert nicht `green`, sondern `green, yellow` lautet. Und weil wir ein Formularfeld mit Mehrfachauswahl haben, könnten die Kombinationen auch nur `green` oder `green, blue` oder `green, blue, yellow` usw. sein.

Wir bräuchten also ein zusätzliches Token, so dass wir am Ende zwei Tokens in unserer Benachrichtigung haben:

| Token name         | Value         |
|--------------------|---------------|
| ##form_colors##    | green, yellow |
| ##is_color_green## | yes           |

Denn dann können wir in unserer Nachricht unser Ziel erreichen und dies schreiben:

> Hallo ##form_firstname## \
> {if is_color_green == "yes"} \
> Sie haben grün ausgewählt! \
> {endif}

## Hinzufügen des eigenen Tokens im Backend

![Screenshot des Tokens im Backend]({{% asset"/notification-center-pro/images/screenshot_is_color_green.png" %}}?classes=shadow)

Gehen wir kurz durch diesen Screenshot:

1. Titel \
   Hier kannst du deinem Token einen internen Titel geben. Dieser wird nur für die Liste im Backend verwendet.
2. Token-Name \
   Hier legst du den Namen deines Tokens fest, den du dann in deinen Benachrichtigungen zur Verfügung haben wirst. Wir verwenden
   `is_color_green`, aber du kannst hier jeden beliebigen Namen verwenden. Stelle nur sicher, dass er nicht mit anderen Tokens kollidiert. Wenn du zum Beispiel mit dem Typ `Contao Formulargenerator-Übertragungen` arbeitest, nenne dein Token nicht
   `form_is_color_green`, da dies zu einem Konflikt führen könnte.
3. Typ der Benachrichtigung \
   Aus genau demselben Grund musst du dein Token einem Benachrichtigungstypen zuordnen. Je nach Benachrichtigungstyp stehen dir unterschiedliche Tokens zur Verfügung und es macht daher keinen Sinn, ein Token mehreren Benachrichtigungstypen zuzuordnen. Wähle hier diejenige aus, mit der du arbeiten möchtest.
4. Typ \
   Hier kannst du wählen, ob du dein Twig-Template nur für dieses Token schreiben oder aus einem
   Template deines Dateisystems auswählen möchtest. Wenn du ein Template erstellst, achte darauf, dass es sich
   innerhalb von `templates/notification_center_pro/custom_token/` befindet - in diesem Fall könntest du es also zum Beispiel
   als `templates/notification_center_pro/custom_token/is_color_green.html.twig` speichern.
5. Schreibe direkt ein Twig-Template nur für dieses Token \
   In unserem Beispiel haben wir das Template jedoch nur für dieses Token direkt eingegeben.

Hier ist der Twig-Code, der im Screenshot zu sehen ist und zum besseren Verständnis mit Kommentaren versehen ist:

```twig
{#
    Wir wollen sicherstellen, dass es keinen Leerraum gibt, sondern nur unser
    Token-Wert ausgegeben wird. Du kannst die Behandlung von Leerzeichen in Twig
    steuern, um genau das zu erreichen, was du möchtest. Siehe
    https://twig.symfony.com/doc/3.x/templates.html#templates-whitespace-control
    für weitere Informationen.
#}
{% apply spaceless %}

{#
    Zunächst einmal ist es immer wichtig, das gesamte Template in eine if-Anweisung
    zu verpacken, um zu prüfen, ob überhaupt ein ##form_colors##-Token angegeben wurde.
    Der einfachste Weg ist die Verwendung der Methode "has()" auf unseren rawTokens.
#}
{% if rawTokens.has('form_colors') %}

{#
    Hier ist ein Beispiel, wie du eine Zeichenkette mit ", " trennen kannst, wenn
    sie das Format "green, red, blue" hat.
#}
{% set colors = parsedTokens.form_colors|split(', ') %}

{#
   Wir haben nun in dieser Vorlage eine Variable mit dem Namen "colors" definiert,
   die nun ein Array enthält. Wir können also unseren gewünschten Token-Inhalt erreichen,
   indem wir prüfen, ob "green" Teil dieses Arrays ist und entsprechend "yes" oder
   "no" ausgeben.
   Natürlich ist das Speichern in einer zusätzlichen Variable optional. Es dient hier
   lediglich zur Veranschaulichung, wie komplexere Templates geschrieben werden könnten.
#}
{% if 'green' in colors %}yes{% else %}no{% endif %}

{% endif %}
{% endapply %}
```

Das war's, du hast jetzt ein Token `is_color_green`, das entweder `yes` oder `no` enthält. Herzlichen Glückwunsch! 🎉
