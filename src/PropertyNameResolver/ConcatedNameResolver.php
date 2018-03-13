<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

final class ConcatedNameResolver implements ConcatedNameResolverInterface
{
    /**
     * @var string
     */
    private $propertyPrefix;

    /**
     * @param string $propertyPrefix
     */
    public function __construct(string $propertyPrefix)
    {
        $this->propertyPrefix = $propertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePropertyName(string $suffix): string
    {
        return strtolower($this->propertyPrefix . '_' . $suffix);
    }
}
