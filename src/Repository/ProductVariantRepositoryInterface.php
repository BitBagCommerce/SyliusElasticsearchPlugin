<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

interface ProductVariantRepositoryInterface
{
    public function findOneByOptionValue(ProductOptionValueInterface $productOptionValue): ?ProductVariantInterface;

    public function findByOptionValue(ProductOptionValueInterface $productOptionValue): array;
}
