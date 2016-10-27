<?php
namespace Sitegeist\NeosGuidelines\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                        *
 *                                                                        */
 
class FileUtilities
{

    /**
     * Checks if a file exists
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileExists($fileName) {
        $file = $this->getRootDir() . '/' . $fileName;

        return file_exists($file);
    }

    /**
     * Checks if a file is in Git
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileIsInVCS($fileName) {
        $file = $this->getRootDir() . '/' . $fileName;
        $value = intval(shell_exec('git ls-files ' . $file . ' --error-unmatch; echo $?'));

        return $value === 0;
    }

    /**
     * get the root dir of the project
     *
     * CAUTION currently only working when called from root dir
     *
     * @return string
     */
    private function getRootDir() {
        return FLOW_PATH_ROOT;
    }
}
