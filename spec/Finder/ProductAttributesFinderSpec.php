<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
        $this->shouldImplement(ProductAttributesFinderInterface::class);
    }

    function it_finds_by_taxon(
        TaxonInterface $taxon,
        QueryBuilderInterface $attributesByTaxonQueryBuilder,
        FinderInterface $attributesFinder,
        AbstractQuery $query
    ): void {
        $taxon->getCode()->willReturn('book');

        $attributesByTaxonQueryBuilder->buildQuery(['taxons' => 'book'])->willReturn($query);

        $attributesFinder->find($query, 20)->willReturn([]);

        $this->findByTaxon($taxon)->shouldBeEqualTo([]);
    }
}
