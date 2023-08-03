<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(function (string $extensionKey): void {
    /**
     * Add default TypoScript (constants and setup)
     */
    ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        '[werkraum] Site Package für travelhub'
    );
}, 'site_package');
