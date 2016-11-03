<?php
namespace Sitegeist\NeosGuidelines\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class GuidelinesCommandController extends CommandController
{
    
    /*
     * Files which are mandatory in the repo
     */
    const EDITORCONFIG = '.editorconfig';
    const COMPOSER_LOCK = 'composer.lock';
    const README = 'README.md';

    /*
     * Sections which are mandatory in the README file
     */
    const SETUP = 'Installation';
    const VCS = 'Versionskontrolle';
    const DEPLOYMENT = 'Deployment';

    /**
     * @Flow\Inject
     * @var \Sitegeist\NeosGuidelines\Utility\Utilities
     */
    protected $utilities;

    /**
     * Validate the current project against the Sitegeist Neos Guidelines
     *
     * @return void
     */
    public function validateCommand()
    {
        $files = array(
            self::EDITORCONFIG,
            self::COMPOSER_LOCK,
            self::README
        );

        $readmeSections = array(
            self::SETUP,
            self::VCS,
            self::DEPLOYMENT
        );

        $this->lintJavascript();

        foreach ($files as $file) {
            if (!$this->utilities->fileExistsAndIsInVCS($file)) {
                throw new \Exception(
                    'No ' . $file . ' found in your project. 
                    If this file is there check if it is in your VCS.'
                );
            }
        }
        
        $readme = file($this->utilities->getAbsolutFilePath(self::README));
        $readme = $this->utilities->getReadmeSections($readme);
        foreach ($readmeSections as $readmeSection) {
            if (!in_array($readmeSection, $readme)) {
                throw new \Exception(
                    'No ' . $readmeSection . ' section found in your ' . self::README . '.'
                );
            }
        }
    }

    private function lintJavascript()
    {
        $versionedPackageJsons = explode("\n", shell_exec('git ls-tree -r master --name-only | grep package.json'));
        $lintCommand = 'npm run lint &> /dev/null';
        
        foreach ($versionedPackageJsons as $packageJSON) {
            if ($packageJSON) {
                $packageJSONPath = substr($packageJSON, 0, strlen($packageJSON) - strlen('package.json'));
                if ($packageJSONPath) {
                    $command = 'cd ' . $packageJSONPath . ' && ' . $lintCommand;
                    system($command, $lintValue);
                } else {
                    system($lintCommand, $lintValue);
                }

                if ($lintValue != 0) {
                    throw new \Exception(
                        'The command: "' . $command . '" returned a non zero exit value'
                    );
                }
            }
        }

        return;
    }
}
