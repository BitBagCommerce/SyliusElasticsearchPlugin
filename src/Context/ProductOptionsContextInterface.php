<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use Sylius\Component\Product\Model\ProductOptionInterface;

interface ProductOptionsContextInterface
{
    /**
     * @return array|ProductOptionInterface[]|null
     */
    public function getOptions(): ?array;
}
