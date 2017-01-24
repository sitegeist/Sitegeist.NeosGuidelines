<?php
namespace Sitegeist\NeosGuidelines\Validators\Distribution;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\PackageInterface;
use Neos\Flow\Package\PackageManagerInterface;
use Neos\Error\Messages\Result;

/**
 * Class AbstractPackageValidator
 * @package Sitegeist\NeosGuidelines\Validators\Package
 */
abstract class AbstractDistributionValidator
{

    /**
     * @Flow\Inject
     * @var PackageManagerInterface
     */
    protected $packageManager;

    /**
     * AbstractPackageValidator constructor.
     * @param mixed $options
     * @return Result
     */
    public function validate($options = []) {
        return $this->validateDistribution($options);
    }

    /**
     * @param mixed $options
     * @return Result
     */
    abstract public function validateDistribution($options);
}