# Wie es funktioniert

Willkommen im Shop für Contao-Erweiterungen der terminal42 gmbh!

Auf dieser Seite möchten wir dir erklären, wie unser Shop und unser Lizenzmodell funktioniert. Ausserdem zeigen wir 
dir, was dich nach der Registrierung erwartet und wie du deine Lizenzen verwalten kannst.

## Lizenzen erwerben

Bei uns gilt jede erworbene Lizenz für genau eine, produktiv genutzte Contao-Installation. Das bedeutet:

* Die Anzahl der Domains, die auf diese Installation zeigen, sind für uns nicht relevant. Entsprechend wird eine 
  Lizenz auch nicht an eine Domain gebunden.
* Zusätzlich zum Produktivsystem, darfst du die Erweiterung auf einer beliebigen Anzahl an Entwicklungssystemen 
  installieren. Dabei ist es egal, ob sich diese bei dir lokal auf deinem Rechner oder auf einem Entwicklungsserver befindet.
* Lizenzen können entweder für ein neues «Projekt» erworben oder zu einem bestehenden «Projekt» hinzugefügt werden.

## Begriffsdefinition: «Projekt» und «Installation»

Wir wiederholen: Jede Lizenz bezieht sich auf genau eine, produktiv genutzte Contao-Installation. Wir müssen also 
den Begriff «Contao-Installation» definieren: Wir unterscheiden in unserem Shop zwischen «Projekt» und «Installation».

Eine «Installation» bezeichnet eine Contao-Installation mit der dazugehörigen Datenbank. Jede «Installation» gehört 
zu einem «Projekt». In den allermeisten Fällen bleibt dies eine 1:1-Beziehung. Beispiel:

> Auf «https://www.terminal42.ch» läuft eine Contao-Installation. Egal wie viele Domains da noch drauf zeigen, es 
> ist und bleibt eine einzige «Installation». Gleichzeitig bildet diese Installation das Projekt «www.terminal42.ch».


Für jede eingesetzte Erweiterung werden die Lizenzkosten also genau **1x fällig**.

Es gibt aber auch Fälle, bei denen das selbe «Projekt» mehrfach installiert wird. Dann wird daraus eine 
1:n-Beziehung. Beispiel:

> Wir haben eine imaginäre Plattform auf Basis von Contao namens «Bakery» entwickelt. Sie bringt diverse 
> Individualisierungen für Bäckereien, wie bspw. spezielle Inhaltselemente für Backwerk. Diese Plattform nutzt 
> kommerzielle terminal42-Erweiterungen und wir installieren sie für diverse Kunden auf verschiedenen Servern. Wir 
> haben also ein «Projekt» namens «Bakery», das wir beliebig oft installieren. Zurzeit nutzen diese Plattform 
> insgesamt 18 Kunden:
> 
> * 1. Bäckerei Schmidt, Hamburg
> * 2. Bäckerei & Konditorei Huber, München
> * 3. [...]
> * 17. Kafi & Gipfeli, Bern
> * 18. Kaiser & Schmarrn, Wien

Da das «Projekt» 18 Mal eingesetzt wird (= 18 produktiv genutzte «Installationen»), werden die Lizenzkosten 
entsprechend **18x fällig**. Sobald wir das Projekt für einen weiteren Kunden installieren, wird eine **19. Lizenz** 
fällig etc.

## Die zwei Installationsmöglichkeiten

Unsere Erweiterungen können allesamt auf zwei verschiedene Arten installiert werden:

{{< tabs groupId="installation-possibilities" >}}

{{% tab name="Installation mit dem Contao Manager" %}}

In deiner Projektübersicht findest du ein entsprechendes ZIP-Paket für deine Lizenz, das du einfach per Drag n' Drop 
in den Manager ziehen kannst. Dieses Paket enthält lediglich die Lizenzinformationen und erlaubt dir den Zugriff auf 
deine lizenzierten Erweiterungen.

{{% /tab %}}

{{% tab name="Installation via Composer auf der CLI" %}}

In deiner Projektübersicht findest du die Angaben für den repositories-Teil. Wir empfehlen, die Zugangsdaten in die 
auth.json zu kopieren. Weitere Informationen findest du im [Handbuch von Composer][Composer_Manual].

[Composer_Manual]: https://getcomposer.org/doc/articles/authentication-for-private-packages.md

{{% /tab %}}

{{< /tabs >}}