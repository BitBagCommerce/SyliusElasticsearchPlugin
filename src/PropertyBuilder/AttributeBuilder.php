<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
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
        private StringFormatterInterface $stringFormatter
    ) {
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $this->resolveProductAttributes($product, $document);
            }
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

    private function resolveProductAttribute(
        array $attributeConfiguration,
        mixed $attributeValue,
        ProductAttributeValue $productAttribute
    ): array {
        /** @var AttributeInterface $attribute */
        $attribute = $productAttribute->getAttribute();

        if (SelectAttributeType::TYPE === $attribute->getType()) {
            $choices = $attributeConfiguration['choices'];
            if (is_array($attributeValue)) {
                foreach ($attributeValue as $i => $item) {
                    $attributeValue[$i] = $choices[$item][$productAttribute->getLocaleCode()] ?? $item;
                }
            } else {
                $attributeValue = $choices[$attributeValue][$productAttribute->getLocaleCode()] ?? $attributeValue;
            }
        }

        $attributes = [];
        if (is_array($attributeValue)) {
            foreach ($attributeValue as $singleElement) {
                $attributes[] = $this->stringFormatter->formatToLowercaseWithoutSpaces((string) $singleElement);
            }
        } else {
            switch (true) {
                case is_string($attributeValue):
                    $attributes[] = $this->stringFormatter->formatToLowercaseWithoutSpaces($attributeValue);

                    break;
                case $attributeValue instanceof \DateTime:
                    $attributeFormat = $attribute->getConfiguration()['format'] ?? null;
                    $defaultFormat = DateAttributeType::TYPE === $attribute->getStorageType() ?
                        self::DEFAULT_DATE_FORMAT :
                        self::DEFAULT_DATE_TIME_FORMAT
                    ;

                    $attributes[] = $attributeValue->format($attributeFormat ?? $defaultFormat);

                    break;
                default:
                    $attributes[] = $attributeValue;
            }
        }

        return $attributes;
    }

    private function processAttribute(
        AttributeInterface $attribute,
        ProductAttributeValue $productAttribute,
        Document $document
    ): void {
        /** @var string $attributeCode */
        $attributeCode = $attribute->getCode();
        $attributeConfiguration = $attribute->getConfiguration();

        $value = $productAttribute->getValue();
        $documentKey = $this->attributeNameResolver->resolvePropertyName($attributeCode);
        $code = sprintf('%s_%s', $documentKey, $productAttribute->getLocaleCode());

        $values = $this->resolveProductAttribute(
            $attributeConfiguration,
            $value,
            $productAttribute
        );

        $values = in_array($attribute->getStorageType(), [DateAttributeType::TYPE, DatetimeAttributeType::TYPE], true) ?
            ($values[0] ?? $values) :
            $values
        ;

        $document->set($code, $values);
    }
}
