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

    /**
     * Files which are mandatory in the repo
     */
    const EDITORCONFIG = '.editorconfig';
    const COMPOSER_LOCK = 'composer.lock';
    const README = 'README.md';

    /**
     * Sections which are mandatory in the README file
     */
    const SETUP = 'Installation';
    const VCS = 'Versionskontrolle';
    const DEPLOYMENT = 'Deployment';

    /**
     * PHP and JS linting commands and files
     */
    const PHP_LINT_COMMAND = 'composer run-script lint';
    const PHP_LINT_FILE = 'composer.json';
    const PHP_MANDAORY_FILE = 'composer.lock';

    const JS_LINT_COMMAND = 'npm run lint';
    const JS_LINT_FILE = 'package.json';
    const JS_MANDATORY_FILE = 'npm-shrinkwrap.json';

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
            $filePath = $this->utilities->getAbsolutFilePath($file);
            if (!$this->utilities->fileExistsAndIsInVCS($filePath)) {
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
     * pass the lint command and filename name to the lint function
     *
     * @return void
     */
    public function lintJavascriptCommand()
    {
        $this->lint(self::JS_LINT_COMMAND, self::JS_LINT_FILE, self::JS_MANDATORY_FILE);
    }

    /**
     * pass the lint command and filename name to the lint function
     *
     * @return void
     */
    public function lintPhpCommand()
    {
        $this->lint(self::PHP_LINT_COMMAND, self::PHP_LINT_FILE, self::PHP_MANDAORY_FILE);
    }

	/**
	 * Checks if files implement the .editorconfig rules
	 * As fallback for indention tabs are used
	 */
    public function checkEditorConfigCommand()
    {
        $editorconfig = parse_ini_file('.editorconfig', true);

        foreach ($editorconfig as $filePattern => $formattingRules) {
            if ($filePattern != 'root' && $filePattern != '*') {
                // @TODO smth cool like ONE regex pattern or so
                $filePattern = str_replace('*.{', '.*\.(', $filePattern);
                $filePattern = str_replace('}', ')', $filePattern);
                $filePattern = str_replace(',', '|', $filePattern);

                $files = $this->utilities->getVersionedFiles($filePattern);

                if (isset($formattingRules['indent_style']) && $formattingRules['indent_style'] != 1) {
                    $indent_style = $formattingRules['indent_style'];
                }

                foreach ($files as $file) {
                    if ($indent_style == 'space') {
                        $pattern = '(^( +|)\S+.*$|^$)';
                    } else {
                        // this is the pattern for tab
                        // which is used as default
                        $pattern = '(^(\t+|)\S+.*$|^$)';
                    }

                    // grep for extended regex and reverse the matches
                    // so I get only 0 as return value if one or more lines does not fit
                    $command = 'grep -Ev "' . $pattern . '" ' . $file . ' &>/dev/null';
                    system($command, $output);

                    // grep returns 1 if no match was found
                    if ($output != 1) {
                        throw new \Exception(
                            'The file: ' . $file . ' does not seem to implement your editorconfig rules!'
                        );

                    }
                }
            }
        }
    }

    /**
     * Searches for all files with a given filename  which are under VCS
     * and executes a lint script in the directory where the file is
     * located
     *
     * @param string $lintCommand
     * @param string $filename
     * @return void
     */
    protected function lint($lintCommand, $filename, $mandatoryFile = null)
    {
        $files = $this->utilities->getVersionedFiles($filename);

        foreach ($files as $file) {
            $filePath = $this->utilities->getAbsoluteFileDirectory($file);
            $command = 'cd ' . $filePath . ' && ' . $lintCommand . ' &> /dev/null';
            system($command, $lintValue);

            if ($lintValue != 0) {
                throw new \Exception(
                    'The command: "' . $command . '" returned a non zero exit value'
                );
            }

            if ($mandatoryFile != null) {
                if (!$this->utilities->fileExistsAndIsInVCS($filePath . '/' . $mandatoryFile)) {
                    throw new \Exception(
                        'There is no corresponding ' . $mandatoryFile . ' for '  . $file
                    );
                }
            }
        }
    }
}
