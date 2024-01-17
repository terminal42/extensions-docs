---
title: "Backend tools"
weight: 4
---


## Switching the current language

With *ChangeLanguage* version 3, editing multilingual content in the
back end becomes a lot easier thanks to the language switching tool.


### In the page or article tree

By clicking on a page name in the back end page or article tree, Contao
will filter down the tree to the given node and show a breadcrumb menu.

![]({{% asset "/changelanguage/images/backend-breadcrumb.png" %}}?classes=shadow)

If you filter for a page, *ChangeLanguage* will now enable you to easily
switch the node filter to the same page in a different language. This
is especially handy in the article tree to quickly edit the same articles
in multiple languages.

![]({{% asset "/changelanguage/images/backend-articles.png" %}}?classes=shadow)


### In the content element list

The same language switching functionality is available in the list
of content elements when editing page articles.

![]({{% asset "/changelanguage/images/backend-content.png" %}}?classes=shadow)

For *ChangeLanguage* to find the correct article in a foreign language,
one of the following conditions must apply:

1. You have set up links between articles of the master page and its
   translations.
2. There is only one article in the target language page
3. There is only one article in the same column as the current article

If none of these matches, clicking the language will bring you to the
list of articles (article tree) with the target page node filter enabled.
