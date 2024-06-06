<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\Resolver;

interface FacetsResolverInterface
{
    public function resolve(array $data): array;
}
