<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductListDataHandler;
use BitBag\SyliusElasticsearchPlugin\Exception\TaxonNotFoundException;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class ShopProductListDataHandlerSpec extends ObjectBehavior
{
    function let(
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext,
        ProductAttributesFinderInterface $attributesFinder
    ): void {
        $this->beConstructedWith(
            $taxonRepository,
            $localeContext,
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
        LocaleContextInterface $localeContext,
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $taxon->getCode()->willReturn('book');

        $taxonRepository->findOneBySlug('book', 'en')->willReturn($taxon);

        $this->retrieveData([
            'slug' => 'book',
            'name' => 'Book',
            'price' => [],
        ])->shouldBeEqualTo([
            'name' => 'Book',
            'taxons' => 'book',
            'taxon' => $taxon,
        ]);
    }

    function it_throws_taxon_not_found_exception_if_taxon_is_null(
        LocaleContextInterface $localeContext,
        TaxonInterface $taxon
    ): void {
        $localeContext->getLocaleCode()->willReturn('en');

        $taxon->getCode()->willReturn('book');

        $this->shouldThrow(TaxonNotFoundException::class)->during('retrieveData', [['slug' => 'book']]);
    }
}
