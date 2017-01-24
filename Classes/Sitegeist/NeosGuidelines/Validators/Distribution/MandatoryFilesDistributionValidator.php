<?php
namespace Sitegeist\NeosGuidelines\Validators\Distribution;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;

class MandatoryFilesDistributionValidator extends AbstractDistributionValidator
{

    /**
     * @param mixed $options
     * @return Result
     */
    public function validateDistribution( $options)
    {
        $result = new Result();
        if ($options['files'] && is_array($options['files'] )) {
            foreach ($options['files'] as $file) {
                if(!file_exists( FLOW_PATH_ROOT . $file)) {
                    $result->forProperty($file)->addError(new Error(sprintf('Expected file %s is missing in distribution', $file)));
                }
            }
        }
        return $result;
    }
}