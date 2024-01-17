---
title: "Tips & FAQ"
weight: 8
---


### Do not use flags for the language selection
Using flag icons for languages is a very bad idea. Flags represent
<u>countries</u> and not <u>languages</u>. Not every language belongs to
only one country! Would you take the American flag, the British flag or
the Australian flag for English? What flags do you use for _Chinese
Simplified_ and _Chinese Traditional_ (two different forms of writing
for the same Chinese language)?

Read more at https://www.ethnologue.com/about/problem-language-identification

### Use a select menu for language selection
Easy: go to your front end module settings and select _nav_dropdown_ in
the _Navigation template_ option. **BAM!**, you're done :-)

### Customizing alternate link tags
By default, _ChangeLanguage_ will add `<link rel="alternate">`
markup to your page's head section. This will tell Google and other search
engines which pages belong together for better search results.


If you want to customize the alternate tags, you can create a copy of the
`block_alternate_links.html5` template and customize the output.


## Common issues

### You must not have more than one auto_item parameter" error message

This error is a misconfiguration based on all the following conditions:

1. You do have _auto_item_ functionality enabled in the system settings.
2. Multiple modules (e.g. news and events) have the same _Redirect page_ set.
3. There are news and events with the same alias.

To fix the error message, you must correctly set up your page structure and modules.


## Frequently Asked Questions

### Where can I get support for ChangeLanguage?
Please use the official Contao community forums at
https://community.contao.org for support on all our free extensions.

### I have found a bug, where do I report it?
Please use the [GitHub issue tracker][GitHub] 
to report bugs. Please make sure to first check if the problem has already been reported.

### I need feature X, can you add that?
It depends. You can use [GitHub issues][GitHub] to create feature requests. If your business depends on the feature, 
it's best to pay a developer to implement it for you and then give something back to the community.
Make sure to read our [Open Source manifest][OpenSource] on this topic.

### How can I add localization for my native language?
Translations for ChangeLanguage are managed on [Transifex][Transifex].
To add your language, simply register yourself for the [ChangeLanguage project][TransifexProject]
and add the new language. New localizations will be published with each new release by default.


## Known limitations

- The option in Contao 3.5 to disable the use of page aliases
  (`tl_settings.disableAlias`) is no longer supported.

- [GitHub]: https://github.com/terminal42/contao-changelanguage/issues
- [OpenSource]: https://www.terminal42.ch/en/open-source.html
- [Transifex]: https://www.transifex.com
- [TransifexProject]: https://www.transifex.com/terminal42/contao-changelanguage/dashboard/
