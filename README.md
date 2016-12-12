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

This will give you the folling new flow-commands  
--> `./flow guidelines:validate`  
--> `./flow guidelines:lintphp`  
--> `./flow guidelines:lintjavascript`  

## Guidelines

Currently this command checks if a `README.md`, `composer.lock`, `npm-shrinkwrap.json` and `.editorconfig` file exists and is under version-control via git in your project-root.
Also it checks if the `README.md` contains the following sections(headlines(#)):
* Installation
* Deployment
* Versionskontrolle

__Every composer.json and package.json have to implement a lint scirpt.__   
The command will search for every `composer.json` and `package.json` in the project and runs a lint command in the directory where the file
is located.   
For a `package.json` it runs `npm run lint`
For a `composer.json` it runs `composer run-script lint`
