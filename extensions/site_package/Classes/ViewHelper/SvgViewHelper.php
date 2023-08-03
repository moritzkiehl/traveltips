<?php

namespace Werkraum\SitePackage\ViewHelper;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class SvgViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    protected $escapeChildren = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('path', 'string', 'e.g. EXT:site_package/test.svg', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $path = $arguments['path'];

        $path = GeneralUtility::getFileAbsFileName($path);
        if (\is_file($path)) {
            $xml = \simplexml_load_string(\file_get_contents($path));
            return $xml->asXML();
        }
        return '';
    }
}
