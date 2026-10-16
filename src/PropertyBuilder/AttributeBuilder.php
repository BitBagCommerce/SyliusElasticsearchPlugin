<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use function sprintf;
use Sylius\Component\Attribute\AttributeType\DateAttributeType;
use Sylius\Component\Attribute\AttributeType\DatetimeAttributeType;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeValue;

final class AttributeBuilder extends AbstractBuilder
{
    public const DEFAULT_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public const DEFAULT_DATE_FORMAT = 'Y-m-d';

    public function __construct(
        private ConcatedNameResolverInterface $attributeNameResolver,
        private StringFormatterInterface $stringFormatter,
    ) {
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $this->resolveProductAttributes($product, $document);
            },
        );
    }

    private function resolveProductAttributes(ProductInterface $product, Document $document): void
    {
        /** @var ProductAttributeValue $productAttribute */
        foreach ($product->getAttributes() as $productAttribute) {
            $attribute = $productAttribute->getAttribute();
            if (null === $attribute) {
                continue;
            }
            $this->processAttribute($attribute, $productAttribute, $document);
        }
    }

    private function resolveProductAttributeValuesPerLocale(
        AttributeInterface $attribute,
        ProductAttributeValue $productAttribute,
    ): array {
        $value = $productAttribute->getValue();
        if (null === $value) {
            return [];
        }

        $values = is_array($value) ? $value : [$value];
        $result = [];

        if (SelectAttributeType::TYPE === $attribute->getType()) {
            $choices = $attribute->getConfiguration()['choices'] ?? [];

            if ($attribute->isTranslatable()) {
                $locale = $productAttribute->getLocaleCode();
                if (null === $locale) {
                    return [];
                }

                foreach ($values as $item) {
                    $result[$locale][] = $choices[$item][$locale] ?? $item;
                }

                return $result;
            }

            foreach ($values as $item) {
                foreach (($choices[$item] ?? []) as $locale => $label) {
                    $result[$locale][] = $label;
                }
            }

            return $result;
        }

        $locale = $productAttribute->getLocaleCode();
        if (null === $locale) {
            return [];
        }

        $result[$locale] = $values;

        return $result;
    }

    private function processAttribute(
        AttributeInterface $attribute,
        ProductAttributeValue $productAttribute,
        Document $document,
    ): void {
        $documentKey = $this->attributeNameResolver
            ->resolvePropertyName((string) $attribute->getCode());

        $valuesPerLocale = $this->resolveProductAttributeValuesPerLocale(
            $attribute,
            $productAttribute,
        );

        foreach ($valuesPerLocale as $locale => $rawValues) {
            $normalized = $this->normalizeValues($attribute, $rawValues);

            $document->set(
                sprintf('%s_%s', $documentKey, $locale),
                $normalized,
            );
        }
    }

    private function normalizeValues(AttributeInterface $attribute, array $values): array|string
    {
        $normalized = array_map(function ($value) use ($attribute) {
            if (is_string($value)) {
                return $this->stringFormatter->formatToLowercaseWithoutSpaces($value);
            }

            if ($value instanceof \DateTimeInterface) {
                $format = $attribute->getConfiguration()['format'] ?? null;
                $default = DateAttributeType::TYPE === $attribute->getStorageType()
                    ? self::DEFAULT_DATE_FORMAT
                    : self::DEFAULT_DATE_TIME_FORMAT;

                return $value->format($format ?? $default);
            }

            return $value;
        }, $values);

        return in_array(
            $attribute->getStorageType(),
            [DateAttributeType::TYPE, DatetimeAttributeType::TYPE],
            true,
        )
            ? ($normalized[0] ?? $normalized)
            : $normalized;
    }
}
