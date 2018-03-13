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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyValueResolver\AttributeValueResolverInterface;
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
     * @var AttributeValueResolverInterface
     */
    private $attributeValueResolver;

    /**
     * @param ConcatedNameResolverInterface $attributeNameResolver
     * @param AttributeValueResolverInterface $attributeValueResolver
     */
    public function __construct(
        ConcatedNameResolverInterface $attributeNameResolver,
        AttributeValueResolverInterface $attributeValueResolver
    ) {
        $this->attributeNameResolver = $attributeNameResolver;
        $this->attributeValueResolver = $attributeValueResolver;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        $document = $event->getDocument();

        $this->resolveProductAttributes($product, $document);
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

            if (!$document->has($index)) {
                $document->set($index, []);
            }

            $reference = $document->get($index);
            $reference[] = $this->attributeValueResolver->resolve($attributeValue);

            $document->set($index, $reference);
        }
    }
}
