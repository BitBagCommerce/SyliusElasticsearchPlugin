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

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper\AttributesMapperCollectorInterface;
use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeValueRepositoryInterface;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

final class ProductAttributesMapper implements ProductAttributesMapperInterface
{
    public function __construct(
        private ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        private LocaleContextInterface $localeContext,
        private StringFormatterInterface $stringFormatter,
        private TaxonContextInterface $taxonContext,
        /** @var $attributeMapper AttributesMapperCollectorInterface[] */
        private iterable $attributeMapper
    ) {
    }

    public function mapToChoices(ProductAttributeInterface $productAttribute): array
    {
        $configuration = $productAttribute->getConfiguration();

        if (isset($configuration['choices']) && is_array($configuration['choices'])) {
            $choices = [];
            foreach ($configuration['choices'] as $singleValue => $val) {
                $label = $configuration['choices'][$singleValue][$this->localeContext->getLocaleCode()];
                $singleValue = SelectAttributeType::TYPE === $productAttribute->getType() ? $label : $singleValue;
                $choice = $this->stringFormatter->formatToLowercaseWithoutSpaces($singleValue);
                $choices[$label] = $choice;
            }

            return $choices;
        }
        /** @var Taxon $taxon */
        $taxon = $this->taxonContext->getTaxon();
        $attributeValues = $this->productAttributeValueRepository->getUniqueAttributeValues($productAttribute, $taxon);

        foreach ($this->attributeMapper as $mapper) {
            if ($mapper->supports($productAttribute->getType())) {
                return $mapper->map($attributeValues);
            }
        }

        $choices = [];
        array_walk($attributeValues, function ($productAttributeValue) use (&$choices, $productAttribute): void {
            $value = $productAttributeValue['value'];

            $configuration = $productAttribute->getConfiguration();

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
                $choice = is_bool($value) ? var_export($value, true) : $choice;
                $choices[$value] = $choice;
            }
        });
        unset($attributeValues);

        return $choices;
    }
}
