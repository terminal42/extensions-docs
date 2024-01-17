---
title: "Installation"
weight: 1
---


Minimum requirements:

 - Contao 4.9
 - Haste 4.13
 - MultiColumnWizard 3.3


### Installation via Contao Manager

Search for `terminal42/contao-changelanguage` in the Contao Manager and add it
to your installation. Apply changes to update the packages.

### Manual installation

Add a composer dependency for this bundle. Therefore, change in the project root
and run the following:

```bash
composer require terminal42/contao-changelanguage
```

Depending on your environment, the command can differ, i.e. starting with
`php composer.phar …` if you do not have composer installed globally.

Then, update the database via the `contao:migrate` command or the Contao install tool.



[1]: https://getcomposer.org
[2]: https://docs.contao.org/books/manual/3.5/en/05-system-administration/extensions.html
[3]: https://github.com/terminal42/contao-changelanguage/archive/master.zip
[4]: https://github.com/codefog/contao-haste/archive/master.zip
[5]: https://github.com/menatwork/MultiColumnWizard/archive/master.zip
