<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:site_package/Configuration/CKEditor/CKEditor.yaml';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:site_package/Configuration/TSConfig/" extensions="tsconfig">'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
    options {
        # ref: https://docs.typo3.org/typo3cms/TSconfigReference/singlehtml/#pagetree-showpageidwithtitle
        pageTree.showPageIdWithTitle = 1
        clearCache.all = 1
    }
');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['wrc'] = ['Werkraum\\SitePackage\\Components'];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['namespaces']['Werkraum\\SitePackage\\Components'] =
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('site_package', 'Resources/Private/Components');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['entrypoints.json']['Werkraum\\SitePackage\\Components'] =
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('site_package', 'Resources/Public/Assets/entrypoints.json');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['entrypoints.json']['Werkraum\\BootstrapFluidComponents\\Components'] =
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('site_package', 'Resources/Public/Assets/entrypoints.json');

$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] .= ',ico';
