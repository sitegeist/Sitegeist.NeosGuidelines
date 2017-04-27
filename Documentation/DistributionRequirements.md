# Neos - Distribution Requirements

`./flow guidelines:validateDistribution` - validates your neos/flow distribution against the sitegist-guidelines
 
## Mandatory files

The following files are considered as mandatory.

- 'README.md'
- 'composer.json'
- 'composer.lock'
- '.editorconfig'

Hint: This can be adjusted with the Setting `distribution.MandatoryFiles.options.files`.

## README.md Sections

The readme file `README.md`has to contain at least those sections.

- 'Installation'
- 'Versionskontrolle'
- 'Deployment'

Hint: This can be adjusted with the Settings:

 - `distribution.ReadmeSections.options.fileName`
 - `distribution.ReadmeSections.options.requiredSections`

## Composer Settings
 
The Composer file has to contain at least those settings.
 
- 'config.platform.php': to ensure that in different environments composer update yields the same result
- 'scripts.lint'
- 'scripts.test'

Hint: This can be adjusted with the Setting `Sitegeist.NeosGuidelines.distribution.Composer.options.requiredSettings`.