<?php

$isDevelopment = strpos(getenv('TYPO3_CONTEXT'), 'Development') === 0;
$loggingConfiguration = $isDevelopment ? [
    'TYPO3' => [
        'CMS' => [
            'deprecations' => [
                'writerConfiguration' => [
                    5 => [
                        'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                            'disabled' => false,
                        ],
                    ],
                ],
            ],
        ],
    ],
] : [
    'TYPO3' => [
        'CMS' => [
            'deprecations' => [
                'writerConfiguration' => [
                    5 => [
                        'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                            'disabled' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
];

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS'], [
    'BE' => [
        'installToolPassword' => getenv('INSTALL_TOOL_PASSWORD'),
        'debug' => $isDevelopment,
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'driver' => getenv('DATABASE_DRIVER') ?: '',
                'path' => getenv('DATABASE_PATH') ?: '',
                'host' => getenv('DATABASE_HOST') ?: '',
                'port' => getenv('DATABASE_PORT') ?: '',
                'user' => getenv('DATABASE_USER') ?: '',
                'password' => getenv('DATABASE_PASSWORD') ?: '',
                'dbname' => getenv('DATABASE_NAME') ?: '',
            ],
        ],
    ],
    'FE' => [
        'debug' => $isDevelopment,
    ],
    'GFX' => [
        'processor' => 'ImageMagick',
        'processor_allowTemporaryMasksAsPng' => false,
        'processor_colorspace' => 'sRGB',
        'processor_effects' => true,
        'processor_enabled' => true,
        'processor_path' => '/usr/bin/',
        'processor_path_lzw' => '/usr/bin/',
    ],
    'LOG' => $loggingConfiguration,
    'MAIL' => [
        'transport' => getenv('MAIL_TRANSPORT'),
        'transport_sendmail_command' => getenv('MAIL_TRANSPORT_SENDMAIL_COMMAND'),
        'transport_smtp_encrypt' => getenv('MAIL_TRANSPORT_SMTP_ENCRYPT'),
        'transport_smtp_password' => getenv('MAIL_TRANSPORT_SMTP_PASSWORD'),
        'transport_smtp_server' => getenv('MAIL_TRANSPORT_SMTP_SERVER'),
        'transport_smtp_username' => getenv('MAIL_TRANSPORT_SMTP_USERNAME'),
    ],
    'SYS' => [
        'devIPmask' => getenv('DEV_IP_MASK'),
        'displayErrors' => $isDevelopment,
        'exceptionalErrors' => getenv('EXCEPTIONAL_ERRORS'),
        'encryptionKey' => getenv('ENCRYPTION_KEY'),
        'sitename' => getenv('SITE_NAME'),
        'trustedHostsPattern' => getenv('TRUSTED_HOSTS_PATTERN')
    ]
]);

if (getenv('IS_DDEV_PROJECT') == "true") {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['assets']['backend'] = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template']['backend'] = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['backend'] = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['news']['backend'] = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
}
