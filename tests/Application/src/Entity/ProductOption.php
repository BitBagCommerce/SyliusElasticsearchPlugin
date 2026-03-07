<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Entity;

use BitBag\SyliusElasticsearchPlugin\Entity\DisableFacetAwareTrait;
use BitBag\SyliusElasticsearchPlugin\Entity\ProductOptionInterface;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductOption as BaseProductOption;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product_option')]
class ProductOption extends BaseProductOption implements ProductOptionInterface
{
    use DisableFacetAwareTrait;
}
