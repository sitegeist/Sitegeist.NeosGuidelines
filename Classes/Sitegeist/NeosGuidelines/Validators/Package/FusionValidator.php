<?php
namespace Sitegeist\NeosGuidelines\Validators\Package;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Flow\Package\PackageInterface;
use Neos\Utility\Files;
use Neos\Fusion\Core\Parser;

class FusionValidator extends AbstractPackageValidator
{

    /**
     * @param PackageInterface $package
     * @param mixed $options
     * @return Result
     */
    public function validatePackage($package, $options)
    {
        $result = new Result();

        $fusionPath =  $package->getResourcesPath() . 'Private/Fusion';
        if (!file_exists($fusionPath)) {
            return $result;
        }

        $fusionFiles = Files::readDirectoryRecursively($fusionPath, 'fusion');

        $fusionParser = new Parser();

        foreach ($fusionFiles as $fusionFile) {

            $localName = substr($fusionFile, strlen($fusionPath) + 1);
            $localDirName = pathinfo($localName, PATHINFO_DIRNAME);
            $localFileName = pathinfo($localName, PATHINFO_FILENAME);

            // handle exclusion rules
            if (in_array($localName, $options['excludedFileNames'])) {
                continue;
            }

            $fusionAst = $fusionParser->parse(file_get_contents($fusionFile), $fusionFile);

            // no empty Fusion
            if (empty($fusionAst) || array_key_exists('__prototypes', $fusionAst) == false || empty($fusionAst['__prototypes'])) {
                $result->forProperty($localName)->addError(new Error(sprintf(
                    'No fusion prototypes found in file %s in package %s',
                    $localName,
                    $package->getPackageKey()
                )));
                continue;
            }

            // Every fusion defines exactly one prototype
            if (count($fusionAst['__prototypes']) !== 1) {
                $result->forProperty($localName)->addError(new Error(sprintf(
                    '%s Prototypes found in file %s and package %s. Exactly one prototype per file is expected',
                    count($fusionAst['__prototypes']),
                    $localName,
                    $package->getPackageKey()
                )));
                continue;
            }

            // detect the prototype of the current fusion file
            $prototypeName = array_keys($fusionAst['__prototypes'])[0];
            list($prototypeNamespace, $prototypePath) = explode(':', $prototypeName, 2);
            $prototypeNamespaceParts = explode('.', $prototypeNamespace);
            $prototypeNameParts = explode('.', $prototypePath);

            // The Fusion files define prototypes in the allowed prefix
            $allowedFusionPrefixes = $options['allowedFusionPrefixes'];
            if (!empty($allowedFusionPrefixes)) {
                if (!in_array($prototypeNameParts[0], $allowedFusionPrefixes)) {
                    $result->forProperty($localName)->addError(new Error(sprintf(
                        'Prototype %s in file does not start with one of those prefixes %s',
                        $localName,
                        implode(',', $allowedFusionPrefixes)
                    )));
                }
            }

            // the dirname represents the start of the prototype-name
            //
            $namespaceDirMatchCount = 0;
            $fileDirParts = explode('/', $localDirName);
            foreach ($fileDirParts as $key => $fileDirPart) {
                if ($prototypeNameParts[$key] == $fileDirPart) {
                    $namespaceDirMatchCount ++;
                } else {
                    $result->forProperty($localName)->addError(new Error(sprintf(
                        'Prototype-name %s is not represented in directory %s',
                        $prototypeName,
                        $localDirName
                    )));
                    break;
                }
            }

            // the filename represents the end of the prototype-name
            //
            $namespaceFileMatchCount = 0;
            $fileNameParts = explode('.', $localFileName);
            $fileNamePartsInverse = array_reverse($fileNameParts);
            foreach ($fileNamePartsInverse as $key => $fileNamePart) {
                if ($fileNamePart == 'index' && $key == 0) {
                    // ignore index
                } elseif ($prototypeNameParts[count($prototypeNameParts) - 1 - $key] == $fileNamePart) {
                    $namespaceFileMatchCount ++;
                } else {
                    $result->forProperty($localName)->addError(new Error(sprintf(
                        'Prototype-name %s is not represented in filename %s',
                        $prototypeName,
                        $localFileName
                    )));
                    break;
                }
            }

            // the filename and dirname together represents the whole prototype-name
            //
            if (($namespaceDirMatchCount + $namespaceFileMatchCount) < count($prototypeNameParts)) {
                $result->forProperty($localName)->addError(new Error(sprintf(
                    'Prototype-name %s is not fully represented by directory %s and filename %s',
                    $prototypeName,
                    $localDirName,
                    $localFileName
                )));
            }
        }
        return $result;
    }
}
