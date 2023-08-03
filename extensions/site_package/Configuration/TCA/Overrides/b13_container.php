<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

// Documentation for the following function call can be found at: https://github.com/b13/container

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$containerRegistry = GeneralUtility::makeInstance(Registry::class);
$containerRegistry->configureContainer(
    (new ContainerConfiguration(
        'werkraum-50-50-grid',
        'Grid mit zwei Spalten(50/50)',
        '',
        [
            [
                ['name' => 'left', 'colPos' => 200],
                ['name' => 'right', 'colPos' => 201],
            ],
        ]
    ))->setIcon('content-container-columns-2')
);

$containerRegistry->configureContainer(
    (new ContainerConfiguration(
        'werkraum-33-66-grid',
        'Grid mit zwei Spalten(33/66)',
        '',
        [
            [
                ['name' => 'left', 'colPos' => 200],
                ['name' => 'right', 'colPos' => 201, 'colspan' => 4],
            ],
        ]
    ))->setIcon('content-container-columns-2-right')
);

$containerRegistry->configureContainer(
    (new ContainerConfiguration(
        'werkraum-66-33-grid',
        'Grid mit zwei Spalten(66/33)',
        '',
        [
            [
                ['name' => 'left', 'colPos' => 200, 'colspan' => 4],
                ['name' => 'right', 'colPos' => 201],
            ],
        ]
    ))->setIcon('content-container-columns-2-left')
);

$containerRegistry->configureContainer(
    (new ContainerConfiguration(
        'werkraum-33-33-33-grid',
        'Grid mit drei Spalten(33/33/33)',
        '',
        [
            [
                ['name' => 'left', 'colPos' => 200],
                ['name' => 'center', 'colPos' => 201],
                ['name' => 'right', 'colPos' => 202],
            ],
        ]
    ))->setIcon('content-container-columns-3')
);

$containerRegistry->configureContainer(
    (new ContainerConfiguration(
        'werkraum-25-25-25-25-grid',
        'Grid mit 4 Spalten(25/25/25/25)',
        '',
        [
            [
                ['name' => 'left', 'colPos' => 200],
                ['name' => 'center-left', 'colPos' => 201],
                ['name' => 'center-right', 'colPos' => 202],
                ['name' => 'right', 'colPos' => 203],
            ],
        ]
    ))->setIcon('content-container-columns-4')
);
