<?php
namespace Sitegeist\NeosGuidelines\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                        *
 *                                                                        */
 
class FileUtilities
{

    /**
     * Composes fileExists and fileIsInVCS
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileExistsAndIsInVCS($fileName) {
        $file = $this->getAbsolutFilePath($fileName);
        return $this->fileExists($file) && $this->fileIsInVCS($file);
    }

    
    /**
     * Returns the absolut path of a file
     *
     * @param string $fileName
     * @return string
     */
    public function getAbsolutFilePath($fileName) {
        return $this->getRootDir() . $fileName;
    }

    /**
     * Checks if a file exists
     *
     * @param string $fileName
     * @return boolean
     */
    private function fileExists($fileName) {
        return file_exists($fileName);
    }

    /**
     * Checks if a file is in Git
     *
     * @param string $fileName
     * @return boolean
     */
    private function fileIsInVCS($fileName) {
        $value = intval(shell_exec('git ls-files ' . $fileName . ' --error-unmatch &>/dev/null; echo $?'));

        return $value === 0;
    }

    /**
     * Gets the root dir of the project
     *
     * @return string
     */
    private function getRootDir() {
        return FLOW_PATH_ROOT;
    }
}
