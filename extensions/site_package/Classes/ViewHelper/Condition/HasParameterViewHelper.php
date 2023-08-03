<?php

namespace Werkraum\SitePackage\ViewHelper\Condition;

use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class HasParameterViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('parameter', 'string', 'the parameter to look for', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        $parameter = $arguments['parameter'];
        if ($renderingContext instanceof \TYPO3\CMS\Fluid\Core\Rendering\RenderingContext) {
            $request = $renderingContext->getRequest();
            if ($request instanceof Request) {
                $queryParams = $request->getQueryParams();
                $parsedBody = $request->getParsedBody();

                return isset($queryParams[$parameter]) || isset($parsedBody[$parameter]);
            }
        }

        return false;
    }
}
