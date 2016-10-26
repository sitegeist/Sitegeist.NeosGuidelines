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
     * Validate the current project against the Sitegeist Neos Guidelines
     *
     * @return bool
     */
    public function validateCommand() 
    {

        if (!$this->editorConfigExits()) {
            throw new Exception('No Editorconfig found in the root directory of the project');
        }
            
            return true;
    }
    
    
    /**
     * Checks if an editorconfig exists in the root directory of the project
     *
     * @return bool
     */
    private function editorConfigExits() 
    {
        return true;        
    }
}