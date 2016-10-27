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
     * @param $fileName
     * @return boolean
     *
     */
    public function fileExistsAndIsInVCS($fileName) {
        $file = $this->getRootDir() . $fileName;
        return $this->fileExists($file) && $this->fileIsInVCS($file);
    }

    /**
     * Checks if a file exists
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileExists($fileName) {
        return file_exists($fileName);
    }

    /**
     * Checks if a file is in Git
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileIsInVCS($fileName) {
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
