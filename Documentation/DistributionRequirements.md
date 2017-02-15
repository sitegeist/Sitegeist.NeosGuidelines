# Neos - Distribution Requirements

`./flow guidelines:validateDistribution` - validates your neos/flow distribution against the sitegist-guidelines
 
## Mandatory files

The following files are considered as mandatory.

- 'README.md'
- 'composer.json'
- 'composer.lock'
- '.editorconfig'

## README.md Sections

The readme file has to contain at least those sections.

- 'Installation'
- 'Versionskontrolle'
- 'Deployment'

## Composer Settings
 
The Composer file has to contain at least those settings.
 
- 'config.platform.php': to ensure that in different environments composer update yields the same result
- 'scripts.lint'
- 'scripts.test'