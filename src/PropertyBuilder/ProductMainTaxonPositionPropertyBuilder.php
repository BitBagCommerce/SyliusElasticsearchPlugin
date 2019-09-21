<?php

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductMainTaxonPositionPropertyBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $taxonPositionNameResolver;

    public function __construct(ConcatedNameResolverInterface $taxonPositionNameResolver)
    {
        $this->taxonPositionNameResolver = $taxonPositionNameResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $mainTaxon = $product->getMainTaxon();

                if (null === $mainTaxon) {
                    return;
                }

                $document->set(
                    $this->taxonPositionNameResolver->resolvePropertyName($mainTaxon->getCode()),
                    $mainTaxon->getPosition()
                );
            }
        );
    }
}
