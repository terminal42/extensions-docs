---
title: "More Simple Tokens"
weight: 150
---

The Notification Center Pro provides additional Simple Tokens that offer convenient solutions for typical problems.

## The `formoptions_*` token

Imagine you have a form field called `sport` with multiple options. For example, a dropdown with the following setting:

| value | name         |
|-------|--------------|
| fb    | football     |
| tt    | table tennis |
| bb    | basketball   |

By default, a token called `form_sport` is available. If the visitor now selects all three options, this token would contain `fb, tt, bb`. This means that this:

```text
Selected sports: ##form_sport##
```

would end up in this:

```text
Selected sports: fb, tt, bb
```

With the Notification Center Pro, you can simply replace `form_sport` by `formoptions_sport`. Then, your

```text
Selected sports: ##formoptions_sport##
```

will result in 

```text
Selected sports: football, table tennis, basketball
```

It's that simple! 🎉

