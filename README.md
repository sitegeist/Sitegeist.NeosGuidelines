# Sitegeist - Neos Guidelines

WIP

## Usage

`./flow guidelines:validate` - validates your neos/flow project against our guidelines

## Installation

Add this repository to your composer.json
```
    "repositories": [{
        "url": "ssh://git@git.sitegeist.de:40022/sitegeist/Sitegeist.NeosGuidelines.git",
        "type": "vcs"
    }],
```

and run `composer require --dev sitegeist/neosguidelines:dev-master`

This will give you the following new flow-command
--> `./flow guidelines:validate`


```
Validate the current project against the Sitegeist Neos Guidelines

COMMAND:
  sitegeist.neosguidelines:guidelines:validate

USAGE:
  ./flow guidelines:validate [<options>]

OPTIONS:
  --files              check mandatory files
  --composer           validate composer.json and execute lint/test
  --readme             validate readme file
  --editorconfig       check if files implement editorconfig rules

DESCRIPTION:
  If no option is given all checks will be performed
```

## Guidelines
* `--files`:
    Tests if a `README.md`, `composer.json`, `composer.lock` and `.editorconfig` is in your project root directory.

* `--composer`:
    Tests if your `composer.json` is valid json, defines a specific php platform and implements a lint and test script and executes them.

* `--readme`:
    Tests if your `README.md` contains Installation, Versionskontrolle and Deployment as headlines.

* `--editorconfig`:
    Tests if all files which are under your VCS system implements your editorconfig guidelines

