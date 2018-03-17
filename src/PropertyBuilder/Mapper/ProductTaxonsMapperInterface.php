<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use Sylius\Component\Core\Model\ProductInterface;

interface ProductTaxonsMapperInterface
{
    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    public function mapToUniqueCodes(ProductInterface $product): array;
}
