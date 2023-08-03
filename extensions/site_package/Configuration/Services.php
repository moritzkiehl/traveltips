<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Backend\Event\SystemInformationToolbarCollectorEvent;
use TYPO3\CMS\RteCKEditor\Form\Element\Event\AfterPrepareConfigurationForEditorEvent;
use Werkraum\SitePackage\Backend\DeploymentInformationToolbarItem;
use Werkraum\SitePackage\Backend\EnvironmentToolbarItem;
use Werkraum\SitePackage\Command\DatabaseDumpCommand;

return static function (ContainerConfigurator $configurator, ContainerBuilder $containerBuilder): void {
    $services = $configurator->services();

    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->private();

    $services->load('Werkraum\\SitePackage\\', __DIR__ . '/../Classes/*');

    $services->set(DeploymentInformationToolbarItem::class)
        ->tag('event.listener', [
            'identifier' => 'registerDeploymentInformationSystemToolbarItem',
            'event' => SystemInformationToolbarCollectorEvent::class,
        ]);

    $services->set(DatabaseDumpCommand::class)
        ->tag('console.command', [
            'command' => 'database:dump',
            'schedulable' => false,
        ]);

    $services->set(EnvironmentToolbarItem::class)
        ->tag('backend.toolbar.item');

    $services->set(\Werkraum\SitePackage\Backend\InlineCkeditorResources::class)
        ->tag('event.listener', [
            'identifier' => 'inlineCkeditorResources',
            'event' => AfterPrepareConfigurationForEditorEvent::class,
        ]);
};
