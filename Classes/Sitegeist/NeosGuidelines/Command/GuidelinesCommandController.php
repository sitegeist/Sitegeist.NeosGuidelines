<?php
namespace Sitegeist\NeosGuidelines\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/* @TODO GENERAL error handling and error messages */
/* @TODO GENERAL use flow file stuff */
/**
 * @Flow\Scope("singleton")
 */
class GuidelinesCommandController extends CommandController
{
    /**
     * @var string
     * @Flow\InjectConfiguration("distribution")
     */
    protected $distribution;

    /**
     * @Flow\Inject
     * @var \Sitegeist\NeosGuidelines\Utility\Utilities
     */
    protected $utilities;

    /**
     * Validate the current project against the Sitegeist Neos Guidelines
     * If no option is given all checks will be performed
     *
     * @param boolean $files check mandatory files
     * @param boolean $composer validate composer.json and execute lint/test
     * @param boolean $readme validate readme file
     * @param boolean $editorconfig check if files implement editorconfig rules
     *
     * @return void
     */
    public function validateCommand($files = false, $composer = false, $readme = false, $editorconfig = false)
    {
        if ($files) {
            $this->doValidations('files');
        }

        if ($composer) {
            $this->doValidations('composer');
        }

        if ($readme) {
            $this->doValidations('readme');
        }

        if ($editorconfig) {
            $this->doValidations('editorconfig');
        }

        if (!$files && !$composer && !$readme && !$editorconfig) {
            $this->doValidations('all');
        }
    }

    protected function doValidations($type)
    {
        switch (strtolower($type)) {
            case 'files':
                echo 'files';
                $this->checkMandatoryFiles();
                break;
            case 'composer':
                $this->checkComposer();
                break;
            case 'readme':
                $this->checkReadme();
                break;
            case 'editorconfig':
                $this->checkEditorConfig();
                break;
            default:
                $this->checkMandatoryFiles();
                $this->checkComposer();
                $this->checkReadme();
                $this->checkEditorConfig();
        }
    }

    /**
     * Checks if your composer.json implements our guidelines
     */
    public function checkComposer()
    {
        $this->checkComposerScripts();
        $this->checkComposerPlattform();
    }

    /**
     * Checks if all files needed in your repository are in place and in VCS
     *
     * @return void
     */
    public function checkMandatoryFiles()
    {
        foreach ($this->distribution['mandatoryFiles'] as $file => $errorMessage) {
            $filePath = $this->utilities->getAbsolutFilePath($file);

            if (!$this->utilities->fileExistsAndIsInVCS($filePath)) {
                throw new \Exception($errorMessage);
            }
        }
    }

    /**
     * Checks if your readme file contains the needed sections
     *
     * @return void
     */
    public function checkReadme()
    {
        $readme = file($this->utilities->getAbsolutFilePath($this->distribution['readmeFile']));
        $readme = $this->utilities->getReadmeSections($readme);
        foreach ($this->distribution['readmeSections'] as $readmeSection => $errorMessage) {
            if (!in_array($readmeSection, $readme)) {
                throw new \Exception($errorMessage);
            }
        }
    }

    // NOT NEEDED ANYMORE - left here  at first for reference
    /**
     * pass the lint command and filename name to the lint function
     *
     * @return void
     */
    /* public function lintCommand() */
    /* { */

    /*     /1* @TODO abstrac this because its also needed in editorconfig *1/ */
    /*     foreach ($this->packages as $packageKey => $package) { */
    /*         /1* @TODO use a default path *1/ */
    /*         if (!isset($package['path'])) { */
    /*             throw new \Exception('No configuration found for your Package: ' */
    /*                 . $packageKey . '. */
    /*                 At least a path must be configured.'); */
    /*         } */

    /*         $config = $this->getPackageConfigFromKey($packageKey); */

    /*         if ($config == null) { */
    /*             throw new \Exception('Something bad happend while loading the config for ' . $packageKey); */
    /*         } */
    /*     } */
    /* } */

    /**
     * Checks if files implement the .editorconfig rules
     * As fallback for indention tabs are used
     *
     * @return void
     */
    public function checkEditorConfig()
    {
        $editorconfig = parse_ini_file('.editorconfig', true);

        foreach ($editorconfig as $filePattern => $formattingRules) {
            if ($filePattern == '*') {
                if (isset($formattingRules['indent_style']) && $formattingRules['indent_style'] != 1) {
                    $defaultIndentStyle = $formattingRules['indent_style'];
                } else {
                    $defaultIndentStyle = 'tab';
                }

                if (isset($formattingRules['trim_trailing_whitespace'])
                        && $formattingRules['trim_trailing_whitespace'] != 1) {
                    $defaultTrimTrailingWhitespace = $formattingRules['trim_trailing_whitespace'];
                } else {
                    $defaultTrimTrailingWhitespace = true;
                }

                // @TODO IMPLEMENT END OF LINE CHECK

                if (isset($formattingRules['end_of_line']) && $formattingRules['end_of_line'] != 1) {
                    $defaultEndOfLine = $formattingRules['end_of_line'];
                } else {
                    $defaultEndOfLine = 'lf';
                }
            }

            if ($filePattern != 'root' && $filePattern != '*') {
                // @TODO smth cool like ONE regex pattern or so
                $filePattern = str_replace('*.{', '.*\.(', $filePattern);
                $filePattern = str_replace('}', ')', $filePattern);
                $filePattern = str_replace(',', '|', $filePattern);

                $files = $this->utilities->getVersionedFiles($filePattern);

                if (isset($formattingRules['indent_style'])
                        && $formattingRules['indent_style'] != 1) {
                    $indentStyle = $formattingRules['indent_style'];
                } else {
                    $indentStyle = $defaultIndentStyle;
                }

                if (isset($formattingRules['trim_trailing_whitespace'])
                        && $formattingRules['trim_trailing_whitespace'] != 1) {
                    $trimTrailingWhitespace = $formattingRules['trim_trailing_whitespace'];
                } else {
                    $trimTrailingWhitespace = $defaultTrimTrailingWhitespace;
                }

                // @TODO IMPLEMENT END OF LINE CHECK
                if (isset($formattingRules['end_of_line'])
                        && $formattingRules['end_of_line'] != 1) {
                    $endOfLine = $formattingRules['end_of_line'];
                } else {
                    $endOfLine = $defaultEndOfLine;
                }

                foreach ($files as $file) {
                    if ($indentStyle == 'space') {
                        $pattern = '(^( +|)\S+.*$|^$)';
                    } elseif ($indentStyle == 'tab') {
                        $pattern = '(^(\t+|)\S+.*$|^$)';
                    } else {
                        throw new \Exception(
                            "ERROR: not a correct indent_style in your .editorconf. Use only space or tab"
                        );
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

                    if ($trimTrailingWhitespace) {
                        $pattern = '.*\s+$';

                        $command = 'grep "' . $pattern . '" ' . $file . ' &>/dev/null';
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
    }

    // NOT NEEDED ANYMORE - left here  at first for reference
    /**
     * Searches for all files with a given filename  which are under VCS
     * and executes a lint script in the directory where the file is
     * located
     *

     * @param string $lintCommand
     * @param string $filename
     * @return void
     */
    /* protected function lint($lintCommand, $filename, $mandatoryFile = null) */
    /* { */
    /*     $files = $this->utilities->getVersionedFiles($filename); */

    /*     foreach ($files as $file) { */
    /*         $filePath = $this->utilities->getAbsoluteFileDirectory($file); */
    /*         $command = 'cd ' . $filePath . ' && ' . $lintCommand . ' &> /dev/null'; */
    /*         system($command, $lintValue); */

    /*         if ($lintValue != 0) { */
    /*             throw new \Exception( */
    /*                 'The command: "' . $command . '" returned a non zero exit value' */
    /*             ); */
    /*         } */

    /*         if ($mandatoryFile != null) { */
    /*             if (!$this->utilities->fileExistsAndIsInVCS($filePath . '/' . $mandatoryFile)) { */
    /*                 throw new \Exception( */
    /*                     'There is no corresponding ' . $mandatoryFile . ' for '  . $file */
    /*                 ); */
    /*             } */
    /*         } */
    /*     } */
    /* } */

    // NOT NEEDED ANYMORE - left here  at first for reference
    /**
     * Merges the default config with the package config
     *
     * @param string $packageKey
     * @return array
     */
    /* protected function getPackageConfigFromKey($packageKey) */
    /* { */
    /*     if (isset($this->packages[$packageKey]['config'])) { */
    /*         return array_merge($this->packageDefaults, $this->packages[$packageKey]['config']); */
    /*     } else { */
    /*         return $this->packageDefaults; */
    /*     } */
    /* } */

    /**
     * Checks if your composer.json implements the scripts needed to validate your distribution
     *
     * @return void
     */
    public function checkComposerScripts()
    {
        $scripts = $this->distribution['composerScripts'];
        $composerArray = $this->getComposerJsonArray();

        foreach ($scripts as $script => $command) {
            if (!isset($composerArray['scripts'][$script])) {
                throw new \Exception('Your composer.json does not have a ' . $script . ' script');
            }
        }
    }

    /**
     * Checks if composer.json imlpements a php platform
     *
     * @return void
     */
    protected function checkComposerPlattform()
    {
        $composerArray = $this->getComposerJsonArray();

        if (!isset($composerArray['config']['platform']['php'])) {
            throw new \Exception("No plattform is defined in your composer.json");
        }
    }

    /**
     * returns the scripts of your composer.json
     *
     * @return void
     */
    protected function getComposerJsonArray()
    {
        if (file_exists('composer.json')) {
            $composerArray = json_decode(file_get_contents('composer.json'), true);
            if ($composerArray == null) {
                throw new \Exception("Not valid composer.json");
            }

            return $composerArray;
        }
    }
}
