<?php
namespace Sitegeist\NeosGuidelines\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                        *
 *                                                                        */

class Utilities
{

    /**
     * Composes fileExists and fileIsInVCS
     *
     * @param string $fileName
     * @return boolean
     */
    public function fileExistsAndIsInVCS($fileName)
    {
        return $this->fileExists($fileName) && $this->fileIsInVCS($fileName);
    }


    /**
     * Searches for files which are under VCS in the whole project directory
     *
     * @param string $fileName
     * @return array of strings
     */
    public function getVersionedFiles($fileName)
    {
        $root = $this->getRootDir();
        $branch = '$(git rev-parse --abbrev-ref HEAD)';
        $command = 'cd ' . $root . ' && git ls-tree -r ' . $branch . ' --name-only | grep ' . $fileName;
        $fileArray = explode("\n", shell_exec($command));

        // because the last element is always an empty string
        unset($fileArray[sizeof($fileArray) - 1]);

        return $fileArray;
    }

    /**
     * gets the absolute file directory of a project specific directory
     *
     * @param string $projectFilePath
     * @return string
     */
    public function getAbsoluteFileDirectory($projectFilePath)
    {
        $absPath = $this->getAbsolutFilePath($projectFilePath);
        $absolutDirectory = substr($absPath, 0, strrpos($absPath, '/'));

        return $absolutDirectory;
    }


    /**
     * Returns the absolut path of a file
     *
     * @param string $fileName
     * @return string
     */
    public function getAbsolutFilePath($fileName)
    {
        return $this->getRootDir() . $fileName;
    }

    /**
     * Normalize the Readme-sections - remove # and whitespace
     *
     * @param array $readme
     * @return array
     */
    public function getReadmeSections($readme)
    {
        $readmeSections = array();

        foreach ($readme as $readmeLine) {
            if ($this->beginsWith($readmeLine, '#')) {
                // Remove all starting #, any amount of whitespace
                // capture any word character and remove trailing whitespace
                $readmeLine = preg_replace('/^#*\s*(\w+)\s*$/', '${1}', $readmeLine);
                array_push($readmeSections, $readmeLine);
            }
        }

        return $readmeSections;
    }

    /**
     * Checks if a string begins with a specific substr
     *
     * @param string $str
     * @param string $sub
     * @return boolean
     */
    private function beginsWith($str, $sub)
    {
        $str = trim($str);
        $sub = trim($sub);
        return (substr($str, 0, strlen($sub)) === $sub);
    }

    /**
     * Checks if a file exists
     *
     * @param string $fileName
     * @return boolean
     */
    private function fileExists($fileName)
    {
        return file_exists($fileName);
    }

    /**
     * Checks if a file is in Git
     *
     * @param string $fileName
     * @return boolean
     */
    private function fileIsInVCS($fileName)
    {
        $value = intval(shell_exec('git ls-files ' . $fileName . ' --error-unmatch &>/dev/null; echo $?'));
        return $value === 0;
    }

    /**
     * Gets the root dir of the project
     *
     * @return string
     */
    private function getRootDir()
    {
        return FLOW_PATH_ROOT;
    }
}
