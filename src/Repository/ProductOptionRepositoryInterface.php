<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

interface ProductOptionRepositoryInterface
{
    public function findAllWithTranslations(?string $locale): array;
}
