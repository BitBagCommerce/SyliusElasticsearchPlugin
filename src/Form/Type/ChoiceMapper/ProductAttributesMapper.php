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
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface;

final class ProductAttributesMapper implements ProductAttributesMapperInterface
{
    /**
     * @var ProductAttributeValueRepositoryInterface
     */
    private $productAttributeValueRepository;

    /**
     * @var StringFormatterInterface
     */
    private $stringFormatter;

    /**
     * @param ProductAttributeValueRepositoryInterface $productAttributeValueRepository
     * @param StringFormatterInterface $stringFormatter
     */
    public function __construct(
        ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        StringFormatterInterface $stringFormatter
    ) {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->stringFormatter = $stringFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function mapToChoices(ProductAttributeInterface $productAttribute): array
    {
        $attributeValues = $this->productAttributeValueRepository->findBy(['attribute' => $productAttribute]);
        $choices = [];
        array_walk($attributeValues, function (ProductAttributeValueInterface $productAttributeValue) use (&$choices) {
            $value = $productAttributeValue->getValue();
            if (is_array($value)) {
                foreach ($value as $singleValue) {
                    $choices[$singleValue] = $this->stringFormatter->formatToLowercaseWithoutSpaces($singleValue);
                }
            } else {
                $choiceValue = is_string($value) ? $this->stringFormatter->formatToLowercaseWithoutSpaces($value) : $value;
                $choices[$value] = $choiceValue;
            }
        });

        return $choices;
    }
}
