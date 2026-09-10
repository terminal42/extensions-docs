# terminal42 extensions documentation framework

This repository holds the documentation books hosted on https://extensions.terminal42.ch/docs. It contains documentation
for both, commercial as well as free extensions. Feel free to contribute to whichever you like :-)

## Cloning

The project installs the Hugo Relearn theme as a git submodule. Thus, when cloning
the repository, you need to use the `--recurse-submodules` parameter:

```bash
git clone --recurse-submodules git@github.com:terminal42/extensions-docs.git
cd extensions-docs
composer install
```

## Updating the Theme

To update the theme after cloning, simply run the following command:

```bash
git submodule foreach git pull origin main
```


## Build

The documentation is built using the [Hugo site generator](https://gohugo.io/),
thus you need to [install Hugo](https://gohugo.io/getting-started/installing/)
first on your system.

Building is handled by a Symfony Console application. There are different commands available
depending on what part of the documentation you want to build.

```bash
./book build <book>
```

Builds one documentation book into the `build` directory. Omit the book argument to build all books.

```bash
./book live <book>
```

Spins up the development server which automatically tracks changes in the `docs`
directory and rebuilds the front end. You can access the front end on [http://localhost:1313](http://localhost:1313).

## Deployment

Copy the environment template and configure the deployment values once:

```bash
cp .env .env.local
```

The local environment file is ignored by Git. Deployment requires `rsync` locally and on the destination server. `DEPLOY_TARGET_PATH` must point to the directory that contains all documentation books. Deploy one book with:

```bash
./book deploy notification-center
```

Omit the book argument to build and deploy all books. This is also the command used by CI. All books are built successfully before the upload starts. Only changed files are transferred and obsolete files are removed from the selected books after their replacements have arrived. Other books below `DEPLOY_TARGET_PATH` remain untouched.

## Protected preview deployment

A single book can be built locally and uploaded to the regular documentation server without publishing its sources. The preview deployment adds HTTP Basic Auth to the generated files only. It does not change the source files or the regular CI build.
Deploy the protected preview with:

```bash
./book deploy-preview notification-center \
    --user=preview \
    --password=temporary-password
```

The command synchronizes only the selected book below `DEPLOY_TARGET_PATH`. Other books remain untouched and obsolete files cannot survive inside the newly deployed book.

The generated password file uses a bcrypt hash and is removed from the local build after the upload. The deployed password file is denied through `.htaccess`. Use HTTPS whenever sharing a preview protected by Basic Auth.
