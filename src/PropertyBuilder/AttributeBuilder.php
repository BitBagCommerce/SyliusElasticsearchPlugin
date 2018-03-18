<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class AttributeBuilder extends AbstractBuilder
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $attributeNameResolver;

    /**
     * @var StringFormatterInterface
     */
    private $stringFormatter;

    /**
     * @param ConcatedNameResolverInterface $attributeNameResolver
     * @param StringFormatterInterface $stringFormatter
     */
    public function __construct(
        ConcatedNameResolverInterface $attributeNameResolver,
        StringFormatterInterface $stringFormatter
    ) {
        $this->attributeNameResolver = $attributeNameResolver;
        $this->stringFormatter = $stringFormatter;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $this->resolveProductAttributes($product, $document);
            });
    }

    /**
     * @param ProductInterface $product
     * @param Document $document
     */
    private function resolveProductAttributes(ProductInterface $product, Document $document): void
    {
        foreach ($product->getAttributes() as $attributeValue) {
            $attributeCode = $attributeValue->getAttribute()->getCode();
            $index = $this->attributeNameResolver->resolvePropertyName($attributeCode);
            $value = $attributeValue->getValue();
            $attributes = [];

            if (is_array($value)) {
                foreach ($value as $singleElement) {
                    $attributes[] = $this->stringFormatter->formatToLowercaseWithoutSpaces((string) $singleElement);
                }
            } else {
                $value = is_string($value) ? $this->stringFormatter->formatToLowercaseWithoutSpaces($value) : $value;
                $attributes[] = $value;
            }

            $document->set($index, $attributes);
        }
    }
}
