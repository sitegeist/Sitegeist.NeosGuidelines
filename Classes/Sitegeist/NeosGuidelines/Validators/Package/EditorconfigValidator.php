<?php
namespace Sitegeist\NeosGuidelines\Validators\Package;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Flow\Package\PackageInterface;
use Neos\Utility\Files;
use Neos\Fusion\Core\Parser;
use TYPO3Fluid\Fluid\Exception;

class EditorconfigValidator extends AbstractPackageValidator
{

    /**
     * @param PackageInterface $package
     * @param mixed $options
     * @return Result
     */
    public function validatePackage($package, $options)
    {
        $result = new Result();

        $editorconfig = parse_ini_file(FLOW_PATH_ROOT . '.editorconfig', true);
        $packagePath = $package->getPackagePath();

        foreach ($options['suffixes'] as $suffix) {

            $matchingEditorconfig = $this->getEditorconfigForSuffix($editorconfig, $suffix);
            $files = Files::readDirectoryRecursively($packagePath, $suffix);

            foreach ($files as $file) {

                $localFile = substr($file, strlen($packagePath));
                $content = file_get_contents($file);

                $exclude = false;
                foreach($options['exclude'] as $excludeName) {
                    if (strpos($localFile, $excludeName) !== false) {
                        $exclude = true;
                    }
                }
                if ($exclude) {
                    continue;
                }

                // check indent style
                if (isset($matchingEditorconfig['indent_style'])) {
                    switch ($matchingEditorconfig['indent_style']) {
                        case 'space':
                            $pattern = "/^(\t +)(\\S+.*)?$/um";
                            break;
                        case 'tab':
                            $pattern = "/^( +)(\\S+.*)?$/um";
                            break;
                        default:
                            $pattern = false;
                    }

                    if ($pattern) {
                        if (preg_match($pattern, $content)) {
                            $result->forProperty($localFile)->addError(new Error(sprintf(
                                'Indent style in file %s does not match expectation %s',
                                $localFile, $matchingEditorconfig['indent_style']
                            )));
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $editorconfig
     * @param $suffix
     * @return array
     */
    protected function getEditorconfigForSuffix($editorconfig, $suffix)
    {
        $matchingEditorconfigKey = false;
        foreach (array_keys($editorconfig) as $editorconfigKey) {
            if (strpos($editorconfigKey, '*.' . $suffix) !== FALSE) {
                $matchingEditorconfigKey = $editorconfigKey;
            }
        }

        if ($matchingEditorconfigKey == false) {
            throw new Exception(sprintf('no editorconfig found for suffix %s', $suffix));
        } else {
            return $editorconfig[$matchingEditorconfigKey];
        }
    }
}