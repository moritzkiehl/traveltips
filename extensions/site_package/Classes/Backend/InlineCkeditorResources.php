<?php

namespace Werkraum\SitePackage\Backend;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\RteCKEditor\Form\Element\Event\AfterPrepareConfigurationForEditorEvent;
use Werkraum\ViteForTypo3\Vitejs\ComponentJsonFile;
use Werkraum\ViteForTypo3\Vitejs\ComponentsJsonFactory;

class InlineCkeditorResources
{
    public function __construct(
        protected PageRenderer $pageRenderer
    ) {
    }

    public function __invoke(AfterPrepareConfigurationForEditorEvent $event): void
    {
        $componentJsonFile = ComponentsJsonFactory::resolve('Werkraum\\SitePackage\\Components');
        if ($componentJsonFile instanceof ComponentJsonFile) {
            if ($viteDevServer = $componentJsonFile->getViteDevServer()) {
                $this->pageRenderer->addJsFile(
                    "{$viteDevServer->getOrigin()}/@vite/client",
                    'module'
                );
            }

            $entry = $componentJsonFile->findEntryForEntryAlias('CKEditor');
            if ($entry === null) {
                return;
            }
            foreach ($entry->getJs() as $file) {
                if ($viteDevServer) {
                    $path = $file;
                } else {
                    $path = PathUtility::getAbsoluteWebPath(PathUtility::getAbsolutePathOfRelativeReferencedFileOrPath($componentJsonFile->getPathSitePrefix() . '/', $file));
                }
                $this->pageRenderer->addJsFooterFile($path, 'module');
            }
            foreach ($entry->getCss() as $file) {
                if ($viteDevServer) {
                    $path = $file;
                } else {
                    $path = PathUtility::getAbsoluteWebPath(PathUtility::getAbsolutePathOfRelativeReferencedFileOrPath($componentJsonFile->getPathSitePrefix() . '/', $file));
                }
                if ($viteDevServer) {
                    $this->pageRenderer->addJsFooterFile($path, 'module');
                } else {
                    $this->pageRenderer->addCssFile($path);
                }
            }
        }
    }
}
