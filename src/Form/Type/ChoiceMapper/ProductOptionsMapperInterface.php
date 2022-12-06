<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper;

use Sylius\Component\Product\Model\ProductOptionInterface;

interface ProductOptionsMapperInterface
{
    public function mapToChoices(ProductOptionInterface $productOption): array;
}
