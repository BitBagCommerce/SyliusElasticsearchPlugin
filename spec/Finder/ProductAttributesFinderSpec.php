<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use FOS\ElasticaBundle\Finder\FinderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductAttributesFinderSpec extends ObjectBehavior
{
    function let(
        FinderInterface $attributesFinder,
        QueryBuilderInterface $attributesByTaxonQueryBuilder
    ): void {
        $this->beConstructedWith(
            $attributesFinder,
            $attributesByTaxonQueryBuilder,
            'taxons'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductAttributesFinder::class);
    }

    function it_implements_product_attributes_finder_interface(): void
    {
        $this->shouldHaveType(ProductAttributesFinderInterface::class);
    }

    function it_finds_by_taxon(
        TaxonInterface $taxon,
        QueryBuilderInterface $attributesByTaxonQueryBuilder,
        FinderInterface $attributesFinder,
        AbstractQuery $query
    ): void {
        $taxon->getCode()->willReturn('book');

        $attributesByTaxonQueryBuilder->buildQuery(['taxons' => 'book'])->willReturn($query);

        $attributesFinder->find($query)->willReturn([]);

        $this->findByTaxon($taxon)->shouldBeEqualTo([]);
    }
}
