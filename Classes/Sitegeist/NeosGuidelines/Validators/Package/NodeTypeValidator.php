<?php
namespace Sitegeist\NeosGuidelines\Validators\Package;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Flow\Package\PackageInterface;
use Neos\Utility\Files;
use Symfony\Component\Yaml\Yaml;

class NodeTypeValidator extends AbstractPackageValidator
{

    /**
     * @param PackageInterface $package
     * @param mixed $options
     * @return Result
     */
    public function validatePackage($package, $options)
    {
        $result = new Result();

        $configurationPath = $package->getConfigurationPath();
        $configurationFiles = Files::readDirectoryRecursively($configurationPath, 'yaml');
        foreach ($configurationFiles as $configurationFile) {
            if (strpos($configurationFile, $configurationPath . 'NodeTypes.') === 0) {
                $name = substr($configurationFile, strlen($configurationPath . 'NodeTypes.'), -1 * strlen('.yaml'));
                $nameParts = explode('.', $name);

                $configuration = Yaml::parse($configurationFile);

                // Empty Configuration
                if (empty($configuration)) {
                    $result->forProperty($name)->addError(new Error(sprintf(
                        'NodeTypes file %s',
                        $name
                    )));
                    continue;
                }

                // One NodeType per NodeTypes.*.yaml
                if (count($configuration) !== 1) {
                    $result->forProperty($name)->addError(new Error(sprintf(
                        '%s NodeTypes found in file %s. Exactly one nodetype per file is expected',
                        count($configuration),
                        $name
                    )));
                }

                // NodeTypes start with an allowed prefix
                $allowedNodeTypePrefixes = $options['allowedNodeTypePrefixes'];
                if (!empty($allowedNodeTypePrefixes)) {
                    if (!in_array($nameParts[0], $allowedNodeTypePrefixes)) {
                        $result->forProperty($name)->addError(new Error(sprintf(
                            'NodeType %s in file %s does not start with one of those prefixes %s',
                            $name,
                            $name,
                            implode(',', $allowedNodeTypePrefixes)
                        )));
                    }
                }

                // Override NodeTypes are defining stuff for other packages
                if ($nameParts[0] === 'Override') {
                    $nodeTypeName = arrray_keys($configuration)[0];
                    if (strpos($package->getPackageKey(), $nodeTypeName) === 0) {
                        $result->forProperty($name)->addError(new Error(sprintf(
                            'Override-NodeType %s in file %s should override NodeTypes in foreign package namespace.',
                            $nodeTypeName,
                            $name
                        )));
                    }
                    // skip the following rules for Override
                    continue;
                }

                // NodeTypes have a name part after the prefix
                if (count($nameParts) < 2) {
                    $result->forProperty($name)->addError(new Error(sprintf(
                        'NodeType %s in file %s is not seperated in Prefix.Name',
                        $name,
                        $name
                    )));
                }

                // Abstract NodeTypes are declacred abstract
                $abstractNodeTypePrefixes = $options['abstractNodeTypePrefixes'];
                if (!empty($abstractNodeTypePrefixes)) {
                    if (in_array($nameParts[0], $abstractNodeTypePrefixes)
                        && (!array_key_exists('abstract', $configuration) || $configuration['abstract'] !== false)
                    ) {
                        $result->forProperty($name)->addError(new Error(sprintf(
                            'NodeType %s with one of those prefixes %s have to be abstract',
                            $name,
                            implode(',', $abstractNodeTypePrefixes)
                        )));
                    }
                }

                // NodeTypes are named PackageKey:Name
                $expectedNodeType = $package->getPackageKey() . ':' . $name;
                if (!array_key_exists($expectedNodeType, $configuration)) {
                    $result->forProperty($name)->addError(new Error(sprintf(
                        'Expected NodeType %s in file %s but found %s',
                        $expectedNodeType,
                        $name,
                        implode(',', array_keys($configuration))
                    )));
                }
            }
        }
        return $result;
    }
}
