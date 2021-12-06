<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class ProductOptionsMapper implements ProductOptionsMapperInterface
{
    /** @var StringFormatterInterface */
    private $stringFormatter;

    public function __construct(StringFormatterInterface $stringFormatter)
    {
        $this->stringFormatter = $stringFormatter;
    }

    public function mapToChoices(ProductOptionInterface $productOption): array
    {
        $productOptionValues = $productOption->getValues()->toArray();
        $choices = [];

        array_walk($productOptionValues, function (ProductOptionValueInterface $productOptionValue) use (&$choices): void {
            $value = $productOptionValue->getValue();
            $choices[$value] = $this->stringFormatter->formatToLowercaseWithoutSpaces($value);
        });

        return $choices;
    }
}
