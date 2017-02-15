# Sitegeist - Neos Guidelines

A package to validate projects against the sitegeist-project-guidelines.

### Authors & Sponsors

* Max Strübing - struebing@sitegeist.de
* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored
by our employer http://www.sitegeist.de.*

## Usage

- `./flow guidelines:validateDistribution` - validates your neos/flow distribution against the sitegist-guidelines
- `./flow guidelines:validatePackages` - validates your neos/flow packages against the sitegist-guidelines

## Installation

Add this repository to your composer.json
```
    "repositories": [{
        "url": "ssh://git@git.sitegeist.de:40022/sitegeist/Sitegeist.NeosGuidelines.git",
        "type": "vcs"
    }],
```

and run `composer require --dev sitegeist/neosguidelines:dev-master`

## Configuration
 
The configuration is done via settings in the path `Sitegeist.NeosGuidelines:`

## Documentation
 
- [Neos - Installation Requirements](Documentation/InstallationRequirements.md)
- [Neos - Distribution Requirements](Documentation/DistributionRequirements.md)
- [Neos - Package Requirements](Documentation/PackageRequirements.md)


 

