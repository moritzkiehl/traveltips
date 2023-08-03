<?php

$token = $_POST['token'];

if ($token !== "b3a57718a4753bbd0c586aba2a142c8173c81c878804390845fd1c4076317d5f") {
    http_send_status(500);
    throw new \Exception('invalid access');
}

call_user_func(static function () {
    // TODO adapt to your need!
    $classLoader = require dirname(__DIR__) . '/vendor/autoload.php';

    \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::run(0, \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::REQUESTTYPE_FE);
    \TYPO3\CMS\Core\Core\Bootstrap::init($classLoader);

    $context = [];

    $version = (new \TYPO3\CMS\Core\Information\Typo3Version);
    $context ['version']['branch'] = $version->getBranch();
    $context ['version']['version'] = $version->getVersion();
    $context ['version']['major'] = $version->getMajorVersion();

    $context ['server'] = $_SERVER['SERVER_SOFTWARE'];

    $context ['os'] = PHP_OS;
    $context ['os_family'] = PHP_OS_FAMILY;
    $context ['composerMode'] = \TYPO3\CMS\Core\Core\Environment::isComposerMode();

    $context ['php']['version'] = PHP_VERSION;
    $context ['php']['sapi'] = PHP_SAPI;
    $context ['php']['loadedExtension'] = implode(', ', get_loaded_extensions());

    foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getConnectionNames() as $connectionName) {
        try {
            $serverVersion = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
                ->getConnectionByName($connectionName)
                ->getServerVersion();

            $context ['db'][] = [
                'connection' => $connectionName,
                'version' => $serverVersion
            ];
        } catch (\Exception $exception) {

        }
    }

    $context ['applicationContext'] = getenv('TYPO3_CONTEXT') ?: (string)\TYPO3\CMS\Core\Core\Environment::getContext();

    echo json_encode($context, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
});
