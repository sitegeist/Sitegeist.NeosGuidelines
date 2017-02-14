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
            // exclude Root.fusion
            if ($fusionFile == $package->getResourcesPath() . 'Private/Fusion/Root.fusion') {
                continue;
            }

            $name = substr($fusionFile, strlen($fusionPath) + 1);
            $name = str_replace('/', '.', $name);
            $name = preg_replace("/(\\.index)?.fusion$/us", "", $name);
            $nameParts = explode('.', $name);

            $fusionAst = $fusionParser->parse(file_get_contents($fusionFile));

            // Empty Fusion
            if (empty($fusionAst) || empty($fusionAst['__prototypes'])) {
                $result->forProperty($name)->addError(new Error(sprintf(
                    'No fusion prototypes found in file %s in package %s',
                    $name,
                    $package->getPackageKey()
                )));
                continue;
            }

            // Every fusion defines exactly one prototype
            if (count($fusionAst['__prototypes']) !== 1) {
                $result->forProperty($name)->addError(new Error(sprintf(
                    '%s Prototypes found in file %s and package %s. Exactly one prototype per file is expected',
                    count($fusionAst['__prototypes']),
                    $name,
                    $package->getPackageKey()
                )));
            }

            // The Fusion files define prototypes in the allowed prefix
            $allowedFusionPrefixes = $options['allowedFusionPrefixes'];
            if (!empty($allowedFusionPrefixes)) {
                if (!in_array($nameParts[0], $allowedFusionPrefixes)) {
                    $result->forProperty($name)->addError(new Error(sprintf(
                        'Prototype %s in file does not start with one of those prefixes %s',
                        $name,
                        implode(',', $allowedFusionPrefixes)
                    )));
                }
            }

            $prototypeName = array_keys($fusionAst['__prototypes'])[0];
            list($prototypeNamespace, $prototypePath) = explode(':', $prototypeName, 2);

            // Prototypes are named as their fusion-file
            if ($prototypePath !== implode('.', $nameParts)) {
                $result->forProperty($name)->addError(new Error(sprintf(
                    'Prototype %s in file %s does not match the expected name %s',
                    $prototypeName,
                    $name,
                    $prototypeNamespace . ':' . implode('.', $nameParts)
                )));
            }
        }
        return $result;
    }
}
