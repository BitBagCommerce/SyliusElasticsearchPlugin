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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonPositionPropertyBuilder extends AbstractBuilder
{
    public function __construct(
        private ConcatedNameResolverInterface $taxonPositionNameResolver
    ) {
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
