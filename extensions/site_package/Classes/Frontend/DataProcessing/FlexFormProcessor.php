<?php

namespace Werkraum\SitePackage\Frontend\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

final class FlexFormProcessor implements DataProcessorInterface
{
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $key = $cObj->stdWrapValue('field', $processorConfiguration, 'pi_flexform');
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'flexform');
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $processedData[$targetVariableName] = $flexFormService->convertFlexFormContentToArray($cObj->data[$key]);
        return $processedData;
    }
}
