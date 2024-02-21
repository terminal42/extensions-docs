# terminal42 extensions documentation framework

This repository holds the documentation books hosted on https://extensions.terminal42.ch/docs. It contains documentation
for both, commercial as well as free extensions. Feel free to contribute to whichever you like :-)

## Cloning

The project installs the Hugo Learn theme as a git submodule. Thus, when cloning
the repository, you need to use the `--recurse-submodules` parameter:

```bash
git clone --recurse-submodules git@github.com:terminal42/extensions-docs.git
```

## Updating the Theme

To update the theme after cloning, simply run the following command:

```bash
git submodule foreach git pull origin master
```


## Build

The documentation is built using the [Hugo site generator](https://gohugo.io/), 
thus you need to [install Hugo](https://gohugo.io/getting-started/installing/) 
first on your system.

Building is done using a PHP file. There are different commands available 
depending on what part of the documentation you want to build.

```
./book build-<book>
```

Builds the entire documentation into the `build` directory.

```
./book live-<book>
```

Spins up the development server which automatically tracks changes in the `docs` 
directory and rebuilds the front end. You can access the front end on [http://localhost:1313](http://localhost:1313).