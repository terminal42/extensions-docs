---
title: "Weitere Simple-Tokens"
url: "weitere-simple-tokens"
weight: 150
---

Das Notification Center Pro liefert weitere Simple-Tokens mit, die bequeme Lösungen für typische Probleme bieten.

## Das `formoptions_*` Token

Stell dir vor, du hast ein Formularfeld namens `sport` mit einer Mehrfachauswahl. Beispielsweise ein Dropdown mit folgender Einstellung:

| Wert | Bezeichnung |
|------|-------------|
| fb   | Fussball    |
| tt   | Tischtennis |
| bb   | Basketball  |

Standardmässig steht dir ein Token namens `form_sport` zur Verfügung. Wenn die Besucherin oder der Besucher nun alle drei Optionen auswählt, würde dieses Token `fb, tt, bb` enthalten. Das heisst aus

```text
Gewählte Sportart(en): ##form_sport##
```

würde 

```text
Gewählte Sportart(en): fb, tt, bb
```

generiert werden.

Mit dem Notification Center Pro kannst du einfach, `form_sport` durch `formoptions_sport` ersetzen. Aus

```text
Gewählte Sportart(en): ##formoptions_sport##
```

wird dann

```text
Gewählte Sportart(en): Fussball, Tischtennis, Basketball
```

So einfach kann es sein! 🎉

