<?php
namespace Sitegeist\NeosGuidelines\Validators\Distribution;

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;

class MarkdownFileDistributionValidator extends AbstractDistributionValidator
{

    /**
     * @param mixed $options
     * @return Result
     */
    public function validateDistribution($options)
    {
        $result = new Result();
        $file = $options['fileName'];
        $fileContent = file_get_contents(FLOW_PATH_ROOT . $file);


        if ($options['requiredSections'] && is_array($options['requiredSections'] )) {
            foreach ($options['requiredSections'] as $section) {
                if (!preg_match('/#+\s*(' . trim($section) . ')/um', $fileContent )) {
                    $result->forProperty($section)->addError(new Error(sprintf('Markdown file %s is missing section %s', $file, $section)));
                }
            }
        }
        return $result;
    }
}