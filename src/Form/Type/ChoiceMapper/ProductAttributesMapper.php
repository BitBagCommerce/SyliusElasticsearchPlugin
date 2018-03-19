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
use Sylius\Component\Locale\Context\LocaleContextInterface;
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
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var StringFormatterInterface
     */
    private $stringFormatter;

    /**
     * @param ProductAttributeValueRepositoryInterface $productAttributeValueRepository
     * @param LocaleContextInterface $localeContext
     * @param StringFormatterInterface $stringFormatter
     */
    public function __construct(
        ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        LocaleContextInterface $localeContext,
        StringFormatterInterface $stringFormatter
    ) {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->localeContext = $localeContext;
        $this->stringFormatter = $stringFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function mapToChoices(ProductAttributeInterface $productAttribute): array
    {
        $attributeValues = $this->productAttributeValueRepository->findBy(['attribute' => $productAttribute]);
        $choices = [];
        array_walk($attributeValues, function (ProductAttributeValueInterface $productAttributeValue) use (&$choices): void {
            $product = $productAttributeValue->getProduct();

            if (!$product->isEnabled()) {
                return;
            }

            $value = $productAttributeValue->getValue();
            $configuration = $productAttributeValue->getAttribute()->getConfiguration();

            if (is_array($value)
                && isset($configuration['choices'])
                && is_array($configuration['choices'])
            ) {
                foreach ($value as $singleValue) {
                    $choice = $this->stringFormatter->formatToLowercaseWithoutSpaces($singleValue);
                    $label = $configuration['choices'][$singleValue][$this->localeContext->getLocaleCode()];
                    $choices[$label] = $choice;
                }
            } else {
                $choice = is_string($value) ? $this->stringFormatter->formatToLowercaseWithoutSpaces($value) : $value;
                $choices[$value] = $choice;
            }
        });

        return $choices;
    }
}
