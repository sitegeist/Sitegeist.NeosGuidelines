<?php
namespace Sitegeist\NeosGuidelines\Validators\Distribution;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Utility\Arrays;

class ComposerFileDistributionValidator extends AbstractDistributionValidator
{

    /**
     * @param mixed $options
     * @return Result
     */
    public function validateDistribution($options)
    {
        $result = new Result();

        $composerConfiguration = json_decode(file_get_contents(FLOW_PATH_ROOT . 'composer.json'), true);
        if ($options['requiredSettings'] && is_array($options['requiredSettings'])) {
            // required settings
            foreach ($options['requiredSettings'] as $requiredSetting) {
                $value = Arrays::getValueByPath($composerConfiguration, $requiredSetting);
                if (is_null($value)) {
                    $message = sprintf('Composer-setting %s is missing', $requiredSetting);
                    $result->forProperty($requiredSetting)->addError(new Error($message));
                }
            }
        }
        return $result;
    }
}
