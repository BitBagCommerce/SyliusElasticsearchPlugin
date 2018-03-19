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

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var ProductTaxonsMapperInterface
     */
    private $productTaxonsMapper;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @param ProductTaxonsMapperInterface $productTaxonsMapper
     * @param string $taxonsProperty
     */
    public function __construct(ProductTaxonsMapperInterface $productTaxonsMapper, string $taxonsProperty)
    {
        $this->productTaxonsMapper = $productTaxonsMapper;
        $this->taxonsProperty = $taxonsProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $taxons = $this->productTaxonsMapper->mapToUniqueCodes($product);

                $document->set($this->taxonsProperty, $taxons);
            }
        );
    }
}
