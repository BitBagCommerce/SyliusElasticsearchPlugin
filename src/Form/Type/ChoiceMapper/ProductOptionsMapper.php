<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class ProductOptionsMapper implements ProductOptionsMapperInterface
{
    /**
     * @var StringFormatterInterface
     */
    private $stringFormatter;

    /**
     * @param StringFormatterInterface $stringFormatter
     */
    public function __construct(StringFormatterInterface $stringFormatter)
    {
        $this->stringFormatter = $stringFormatter;
    }

    /**
     * {@inheritdoc}
     */
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
