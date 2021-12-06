<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonsBuilder extends AbstractBuilder
{
    /** @var ProductTaxonsMapperInterface */
    private $productTaxonsMapper;

    /** @var string */
    private $taxonsProperty;

    public function __construct(ProductTaxonsMapperInterface $productTaxonsMapper, string $taxonsProperty)
    {
        $this->productTaxonsMapper = $productTaxonsMapper;
        $this->taxonsProperty = $taxonsProperty;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $taxons = $this->productTaxonsMapper->mapToUniqueCodes($product);

                $document->set($this->taxonsProperty, $taxons);
            }
        );
    }
}
