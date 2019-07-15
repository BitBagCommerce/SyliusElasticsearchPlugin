<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

interface SearchPropertyNameResolverRegistryInterface
{
    /**
     * @param ConcatedNameResolverInterface $propertyNameResolver
     */
    public function addPropertyNameResolver(ConcatedNameResolverInterface $propertyNameResolver): void;

    /**
     * @return ConcatedNameResolverInterface[]
     */
    public function getPropertyNameResolvers(): array;
}
