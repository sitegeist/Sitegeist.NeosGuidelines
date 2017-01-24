<?php
namespace Sitegeist\NeosGuidelines\Validators\Package;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Flow\Package\PackageInterface;

class MandatoryFilesPackageValidator extends AbstractPackageValidator
{

    /**
     * @param PackageInterface $package
     * @param mixed $options
     * @return Result
     */
    public function validatePackage($package, $options)
    {
        $result = new Result();
        if ($options['files'] && is_array($options['files'] )) {
            foreach ($options['files'] as $file) {
                if(!file_exists( $package->getPackagePath() . $file)) {
                    $result->forProperty($file)->addError(new Error(sprintf('Expected file %s is missing in package %s', $file, $package->getPackageKey())));
                }
            }
        }
        return $result;
    }
}