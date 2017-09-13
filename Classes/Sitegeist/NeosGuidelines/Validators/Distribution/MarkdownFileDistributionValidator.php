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
        $fileExists = file_exists(FLOW_PATH_ROOT . $file);

        if (!$fileExists) {
            $message = sprintf('No %s file found in %s', $file, FLOW_PATH_ROOT);
            $result->addError(new Error($message));

            return $result;
        }

        $fileContent = file_get_contents(FLOW_PATH_ROOT . $file);

        if ($options['requiredSections'] && is_array($options['requiredSections'])) {
            foreach ($options['requiredSections'] as $section) {
                if (!preg_match('/#+\s*(' . trim($section) . ')/um', $fileContent)) {
                    $message = sprintf('Markdown file %s is missing section %s', $file, $section);
                    $result->forProperty($section)->addError(new Error($message));
                }
            }
        }
        return $result;
    }
}
