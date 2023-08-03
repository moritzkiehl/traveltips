<?php

namespace Werkraum\SitePackage\Frontend\DataProcessing;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class ConstantsProcessor
 */
final class ConstantsProcessor implements DataProcessorInterface, SingletonInterface
{
    /**
     * a simple service cache
     */
    private array $_cache = [];

    /**
     * Process content object data
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $key = $cObj->stdWrapValue('key', $processorConfiguration);

        $constants = $this->getTypoScriptConstantsAsArray($key);
        // Set the target variable
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration);
        if (!empty($targetVariableName)) {
            $processedData[$targetVariableName] = $constants;
        } else {
            $processedData['constants'] = $constants;
        }

        return $processedData;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getTypoScriptConstantsAsArray($key = 'site_package')
    {
        if (empty($key)) {
            $key = 'lgs';
        }
        if (isset($this->_cache[$key]) && !empty($this->_cache[$key])) {
            return $this->_cache[$key];
        }

        $flatConstants = '';
        $prefix = $key . '.';
        if ($GLOBALS['TSFE']->tmpl->flatSetup === null
            || !is_array($GLOBALS['TSFE']->tmpl->flatSetup)
            || $GLOBALS['TSFE']->tmpl->flatSetup === []) {
            $GLOBALS['TSFE']->tmpl->generateConfig();
        }
        foreach ($GLOBALS['TSFE']->tmpl->flatSetup as $constant => $value) {
            if (str_starts_with((string)$constant, $prefix)) {
                $flatConstants .= substr((string)$constant, strlen($prefix)) . ' = ' . $value . PHP_EOL;
            }
        }
        $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoScriptParser->parse($flatConstants);
        $typoScriptArray = $typoScriptParser->setup;
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $constants = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptArray);

        // save for later
        $this->_cache[$key] = $constants;
        return $this->_cache[$key];
    }
}
