<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;

class ProductShortDescriptionBuilder extends AbstractBuilder
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $productShortDescriptionNameResolver;

    public function __construct(ConcatedNameResolverInterface $productShortDescriptionNameResolver)
    {
        $this->productShortDescriptionNameResolver = $productShortDescriptionNameResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                /** @var ProductTranslationInterface $productTranslation */
                foreach ($product->getTranslations() as $productTranslation) {
                    $propertyName = $this->productShortDescriptionNameResolver->resolvePropertyName(
                        $productTranslation->getLocale()
                    );
                    $document->set($propertyName, $productTranslation->getShortDescription());
                }
            }
        );
    }
}
