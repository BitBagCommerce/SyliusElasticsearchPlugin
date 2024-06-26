<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

final class ConcatedNameResolver implements ConcatedNameResolverInterface
{
    public function __construct(
        private string $propertyPrefix
    ) {
    }

    public function resolvePropertyName(string $suffix): string
    {
        return strtolower($this->propertyPrefix . '_' . $suffix);
    }
}
