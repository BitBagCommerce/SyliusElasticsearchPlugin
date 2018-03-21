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

use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinder;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use FOS\ElasticaBundle\Finder\FinderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductOptionsFinderSpec extends ObjectBehavior
{
    function let(
        FinderInterface $optionsFinder,
        QueryBuilderInterface $productOptionsByTaxonQueryBuilder
    ): void {
        $this->beConstructedWith(
            $optionsFinder,
            $productOptionsByTaxonQueryBuilder,
            'taxons'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductOptionsFinder::class);
    }

    function it_implements_product_options_finder_interface(): void
    {
        $this->shouldHaveType(ProductOptionsFinderInterface::class);
    }

    function it_finds_by_taxon(
        TaxonInterface $taxon,
        QueryBuilderInterface $productOptionsByTaxonQueryBuilder,
        FinderInterface $optionsFinder,
        AbstractQuery $query
    ): void {
        $taxon->getCode()->willReturn('book');

        $productOptionsByTaxonQueryBuilder->buildQuery(['taxons' => 'book'])->willReturn($query);

        $optionsFinder->find($query)->willReturn([]);

        $this->findByTaxon($taxon)->shouldBeEqualTo([]);
    }
}
