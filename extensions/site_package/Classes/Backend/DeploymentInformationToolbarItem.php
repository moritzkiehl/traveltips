<?php

namespace Werkraum\SitePackage\Backend;

use TYPO3\CMS\Backend\Backend\Event\SystemInformationToolbarCollectorEvent;
use TYPO3\CMS\Core\Core\Environment;

final class DeploymentInformationToolbarItem
{
    /**
     * Adds last deployment date and deployed version to the system information toolbar
     *
     * Reads the file deployment_information from the project root expecting the following structure
     * ```
     * date
     * version
     * ```
     *
     * @param SystemInformationToolbarCollectorEvent $event
     */
    public function __invoke(SystemInformationToolbarCollectorEvent $event): void
    {
        if (!$GLOBALS['BE_USER']->isAdmin()) {
            return;
        }

        $content = @file_get_contents(Environment::getProjectPath() . '/deployment_information');
        if ($content === false) {
            return;
        }
        $deployInfo = explode("\n", $content);
        $event->getToolbarItem()->addSystemInformation(
            'Last Deployment',
            $deployInfo[0],
            'form-file-upload'
        );
        $event->getToolbarItem()->addSystemInformation(
            'Version',
            $deployInfo[1],
            'miscellaneous-placeholder'
        );
    }
}
