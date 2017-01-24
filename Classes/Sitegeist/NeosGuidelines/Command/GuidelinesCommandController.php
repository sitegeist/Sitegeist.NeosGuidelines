<?php
namespace Sitegeist\NeosGuidelines\Command;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "Sitegeist.NeosGuidelines".   *
 *                                                                             *
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Result;
use Neos\Flow\Cli\CommandController;
use Neos\Utility\Arrays;

/**
 * @Flow\Scope("singleton")
 */
class GuidelinesCommandController extends CommandController
{
    /**
     * @var string
     * @Flow\InjectConfiguration("packages")
     */
    protected $packageConfiguration;

    /**
     * @var string
     * @Flow\InjectConfiguration("distribution")
     */
    protected $distributionConfiguration;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\ObjectManagement\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Validate the current project against the Sitegeist-NeosGuidelines
     *
     * @return void
     */
    public function validateDistributionCommand()
    {
        $this->outputLine('<b>Validate Distribution</b>');

        $result = $this->validateDistribution();

        if ($result->hasErrors()) {
            $errors = $result->getFlattenedErrors();
            $this->outputLine('<b>%d errors were found:</b>', [count($errors)]);
            /** @var Error $error */
            foreach ($errors as $path => $pathErrors) {
                foreach ($pathErrors as $error) {
                    $this->outputLine(' - %s -> %s', [$path, $error->render()]);
                }
            }
            $this->quit(1);
        } else {
            $this->outputLine('<b>All Valid!</b>');
        }
    }



    /**
     * Validate the given packages against the Sitegeist-NeosGuidelines
     *
     * @param string $packageKeys check mandatory files
     * @return void
     */
    public function validatePackagesCommand($packageKeys = null)
    {
        if ($packageKeys) {
            $packageKeyList = Arrays::trimExplode(',',$packageKeys);
        } else {
            $packageKeyList = $this->packageConfiguration['packageKeys'];
        }

        $this->outputLine('<b>Validate Packages ' . implode(', ', $packageKeyList) . '</b>');

        $result = new Result();
        foreach ($packageKeyList as $packageKeyToValidate) {
            $result->forProperty($packageKeyToValidate)->merge($this->validatePackage($packageKeyToValidate));
        }

        if ($result->hasErrors()) {
            $errors = $result->getFlattenedErrors();
            $this->outputLine('<b>%d errors were found:</b>', [count($errors)]);
            /** @var Error $error */
            foreach ($errors as $path => $pathErrors) {
                foreach ($pathErrors as $error) {
                    $this->outputLine(' - %s -> %s', [$path, $error->render()]);
                }
            }
            $this->quit(1);
        } else {
            $this->outputLine('<b>All Valid!</b>');
        }
    }

    /**
     * @param $packageKey
     * @return Result
     */
    protected function validateDistribution() {
        $result = new Result();
        $validators = $this->distributionConfiguration['validators'];
        foreach ($validators as $validatorKey => $validatorConfig) {
            $validator = $this->objectManager->get($validatorConfig['validator']);
            $validatorResult = $validator->validate($validatorConfig['options']);
            $result->forProperty($validatorKey)->merge($validatorResult);
        }
        return $result;
    }

    /**
     * @param $packageKey
     * @return Result
     */
    protected function validatePackage($packageKey) {
        $result = new Result();
        $validators = $this->packageConfiguration['validators'];
        foreach ($validators as $validatorKey => $validatorConfig) {
            $validator = $this->objectManager->get($validatorConfig['validator']);
            $validatorResult = $validator->validate($packageKey, $validatorConfig['options']);
            $result->forProperty($validatorKey)->merge($validatorResult);
        }
        return $result;
    }
}
