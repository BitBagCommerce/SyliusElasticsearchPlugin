<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductListDataHandler;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;

final class ShopProductListDataHandlerSpec extends ObjectBehavior
{
    function let(
        TaxonContextInterface $taxonContext,
        ProductAttributesFinderInterface $attributesFinder
    ): void {
        $this->beConstructedWith(
            $taxonContext,
            $attributesFinder,
            'name',
            'taxons',
            'option',
            'attribute'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopProductListDataHandler::class);
    }

    function it_implements_data_handler_interface(): void
    {
        $this->shouldHaveType(DataHandlerInterface::class);
    }

    function it_retrieves_data(
        TaxonContextInterface $taxonContext,
        TaxonInterface $taxon
    ): void {
        $taxonContext->getTaxon()->willReturn($taxon);

        $taxon->getCode()->willReturn('book');

        $this->retrieveData([
            'slug' => 'book',
            'name' => 'Book',
            'price' => [],
            'facets' => [],
        ])->shouldBeEqualTo([
            'name' => 'Book',
            'taxons' => 'book',
            'taxon' => $taxon,
            'facets' => [],
        ]);
    }
}
