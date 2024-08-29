<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AuthenticationManagerPolyfillPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            false === $container->has('security.authentication_manager')
            &&
            true === $container->has('security.authentication.manager')
        ) {
            $container->setAlias('security.authentication_manager', 'security.authentication.manager');
        }
    }
}
