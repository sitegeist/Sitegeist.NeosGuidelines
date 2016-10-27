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
    
    const EDITORCONFIG = '.editorconfig';
    const COMPOSER_LOCK = 'composer.lock';
    const README = 'README.md';

    /**
     * @Flow\Inject
     * @var \Sitegeist\NeosGuidelines\Utility\FileUtilities
     */
    protected $fileUtilities;

    /**
     * Validate the current project against the Sitegeist Neos Guidelines
     *
     * @return void
     */
    public function validateCommand() 
    {
        
        $files = array(self::EDITORCONFIG, self::COMPOSER_LOCK, self::README);

        foreach ($files as $file) {
            if (!$this->fileUtilities->fileExistsAndIsInVCS($file)) {
                throw new \Exception('No ' . $file . ' found in your project. 
                    If this file is there check if it is in your VCS.');
            }
        }
        
        $readme = file(FLOW_PATH_ROOT . self::README);
        if (!in_array("# Installation\n", $readme)) {
            throw new \Exception('No Installation section found in your README.md.');
        } else if (!in_array("# Versionskontrolle\n", $readme)) {
            throw new \Exception('No Versionskontrolle section found in your README.md.');
        } else if (!in_array("# Deployment\n", $readme)) {
            throw new \Exception('No Deployment section found in your README.md.');
        }

    }

}
