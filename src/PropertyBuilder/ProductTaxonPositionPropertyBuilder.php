<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonPositionPropertyBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $taxonPositionNameResolver;

    public function __construct(ConcatedNameResolverInterface $taxonPositionNameResolver)
    {
        $this->taxonPositionNameResolver = $taxonPositionNameResolver;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
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
