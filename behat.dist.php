<?php

declare(strict_types=1);

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\MinkExtension\ServiceContainer\MinkExtension;
use DMore\ChromeExtension\Behat\ServiceContainer\ChromeExtension;
use FriendsOfBehat\MinkDebugExtension\ServiceContainer\MinkDebugExtension;
use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;
use FriendsOfBehat\VariadicExtension\ServiceContainer\VariadicExtension;

return (new Config())
    ->import([
        'vendor/sylius/sylius/src/Sylius/Behat/Resources/config/suites.php',
        'tests/Behat/Resources/suites.php',
    ])
    ->withProfile(
        (new Profile('default'))
        ->withExtension(new Extension(ChromeExtension::class))
        ->withExtension(new Extension(MinkDebugExtension::class, [
            'directory' => 'etc/build',
            'clean_start' => false,
            'screenshot' => true,
        ]))
        ->withExtension(new Extension(MinkExtension::class, [
            'files_path' => '%paths.base%/vendor/sylius/sylius/src/Sylius/Behat/Resources/fixtures/',
            'base_url' => 'https://127.0.0.1:8080/',
            'default_session' => 'symfony',
            'javascript_session' => 'chrome_headless',
            'sessions' => [
                'symfony' => [
                    'symfony' => null,
                ],
                'chrome_headless' => [
                    'chrome' => [
                        'api_url' => 'http://127.0.0.1:9222',
                        'validate_certificate' => false,
                    ],
                ],
                'chrome' => [
                    'selenium2' => [
                        'browser' => 'chrome',
                        'capabilities' => [
                            'browserName' => 'chrome',
                            'browser' => 'chrome',
                            'version' => '',
                            'marionette' => null, // https://github.com/Behat/MinkExtension/pull/311
                            'chrome' => [
                                'switches' => [
                                    'start-fullscreen',
                                    'start-maximized',
                                    'no-sandbox',
                                ],
                            ],
                            'extra_capabilities' => [
                                'unexpectedAlertBehaviour' => 'accept',
                            ],
                        ],
                    ],
                ],
                'firefox' => [
                    'selenium2' => [
                        'browser' => 'firefox',
                    ],
                ],
            ],
            'show_auto' => false,
        ]))
        ->withExtension(new Extension(SymfonyExtension::class, [
            'bootstrap' => 'tests/Application/config/bootstrap.php',
            'kernel' => [
                'class' => \Tests\BitBag\SyliusElasticsearchPlugin\Application\Kernel::class,
            ],
        ]))
        ->withExtension(new Extension(VariadicExtension::class)),
    )
;
