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

        $this->lintJavascriptCommand();
        $this->lintPhpCommand();
    }

    /**
     * Searches for all versioned package.jsons and runs a lint command
     *
     * @return void
     */
    public function lintJavascriptCommand()
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
                    $command = $lintCommand;
                    system($command, $lintValue);
                }

                if ($lintValue != 0) {
                    throw new \Exception(
                        'The command: "' . $command . '" returned a non zero exit value'
                    );
                }
            }
        }
    }

    /**
     * Searches for all versioned composer.jsons and runs a lint command
     *
     * @return void
     */
    public function lintPhpCommand()
    {
        $versionedComposerJsons = explode("\n", shell_exec('git ls-tree -r master --name-only | grep composer.json'));
        $lintCommand = 'composer run-script lint &> /dev/null';
        
        foreach ($versionedComposerJsons as $composerJSON) {
            if ($composerJSON) {
                $composerJSONPath = substr($composerJSON, 0, strlen($composerJSON) - strlen('composer.json'));
                if ($composerJSONPath) {
                    $command = 'cd ' . $composerJSONPath . ' && ' . $lintCommand;
                    system($command, $lintValue);
                } else {
                    $command = $lintCommand;
                    system($command, $lintValue);
                }

                if ($lintValue != 0) {
                    throw new \Exception(
                        'The command: "' . $command . '" returned a non zero exit value'
                    );
                }
            }
        }
    }
}
