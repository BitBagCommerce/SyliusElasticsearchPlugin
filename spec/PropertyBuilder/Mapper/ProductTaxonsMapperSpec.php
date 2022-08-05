<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapper;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductTaxonsMapperSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductTaxonsMapper::class);
    }

    function it_implements_product_taxons_mapper_interface(): void
    {
        $this->shouldHaveType(ProductTaxonsMapperInterface::class);
    }

    function it_maps_to_unique_codes(
        ProductInterface $product,
        Collection $collection,
        TaxonInterface $taxon
    ): void {
        $taxon->getCode()->willReturn('book');

        $taxons = new \ArrayIterator([$taxon]);

        $collection->getIterator()->willReturn($taxons);

        $product->getTaxons()->willReturn($collection);

        $this->mapToUniqueCodes($product);
    }
}
