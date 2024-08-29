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
use Sylius\Component\Core\Model\TaxonInterface;

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
                foreach ($product->getProductTaxons() as $productTaxon) {
                    /** @var TaxonInterface $taxon */
                    $taxon = $productTaxon->getTaxon();

                    /** @var string $code */
                    $code = $taxon->getCode();

                    $document->set(
                        $this->taxonPositionNameResolver->resolvePropertyName($code),
                        $productTaxon->getPosition()
                    );
                }
            }
        );
    }
}
