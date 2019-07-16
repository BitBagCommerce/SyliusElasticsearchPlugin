<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;

final class ProductDescriptionBuilder extends AbstractBuilder
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $productDescriptionNameResolver;

    public function __construct(ConcatedNameResolverInterface $productDescriptionNameResolver)
    {
        $this->productDescriptionNameResolver = $productDescriptionNameResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                /** @var ProductTranslationInterface $productTranslation */
                foreach ($product->getTranslations() as $productTranslation) {
                    $propertyName = $this->productDescriptionNameResolver->resolvePropertyName(
                        $productTranslation->getLocale()
                    );
                    $document->set($propertyName, $productTranslation->getDescription());
                }
            }
        );
    }
}
