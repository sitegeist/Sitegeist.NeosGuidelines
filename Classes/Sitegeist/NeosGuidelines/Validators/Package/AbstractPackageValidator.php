<?php
namespace Sitegeist\NeosGuidelines\Validators\Package;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\PackageInterface;
use Neos\Flow\Package\PackageManagerInterface;
use Neos\Error\Messages\Result;

/**
 * Class AbstractPackageValidator
 * @package Sitegeist\NeosGuidelines\Validators\Package
 */
abstract class AbstractPackageValidator
{

    /**
     * @Flow\Inject
     * @var PackageManagerInterface
     */
    protected $packageManager;

    /**
     * AbstractPackageValidator constructor.
     * @param string $packageKey
     * @param mixed $options
     * @return Result
     */
    public function validate($packageKey, $options = [])
    {
        $package = $this->packageManager->getPackage($packageKey);
        return $this->validatePackage($package, $options);
    }

    /**
     * @param PackageInterface $package
     * @param mixed $options
     * @return Result
     */
    abstract public function validatePackage($package, $options);
}
