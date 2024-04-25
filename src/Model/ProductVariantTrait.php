<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

use Sylius\Component\Core\Model\ProductInterface;

trait ProductVariantTrait
{
    public function getProduct(): ProductInterface
    {
        $product = parent::getProduct();
        $product->addVariant($this);

        return $product;
    }
}
