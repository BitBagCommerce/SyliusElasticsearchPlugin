<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonPositionPropertyBuilder extends AbstractBuilder
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
                foreach ($product->getProductTaxons() as $taxon) {
                    $document->set(
                        $this->taxonPositionNameResolver->resolvePropertyName($taxon->getTaxon()->getCode()),
                        $taxon->getPosition()
                    );
                }
            }
        );
    }
}
