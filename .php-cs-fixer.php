<?php

require 'vendor/autoload.php';

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()->in(__DIR__ . '/extensions/');
return $config;
