<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContext;
use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\Exception\TaxonNotFoundException;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TaxonContextSpec extends ObjectBehavior
{
    function let(
        RequestStack $requestStack,
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext
    ): void {
        $this->beConstructedWith($requestStack, $taxonRepository, $localeContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonContext::class);
    }

    function it_implements_taxon_context_interface(): void
    {
        $this->shouldHaveType(TaxonContextInterface::class);
    }

    function it_gets_taxon(
        RequestStack $requestStack,
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext,
        Request $request,
        TaxonInterface $taxon
    ): void {
        $request->get('slug')->willReturn('book');

        $requestStack->getCurrentRequest()->willReturn($request);

        $localeContext->getLocaleCode()->willReturn('en');

        $taxonRepository->findOneBySlug('book', 'en')->willReturn($taxon);

        $this->getTaxon()->shouldBeEqualTo($taxon);
    }

    function it_throws_taxon_not_found_exception_if_taxon_is_null(
        RequestStack $requestStack,
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext,
        Request $request
    ): void {
        $request->get('slug')->willReturn('book');

        $requestStack->getCurrentRequest()->willReturn($request);

        $localeContext->getLocaleCode()->willReturn('en');

        $taxonRepository->findOneBySlug('book', 'en')->willReturn(null);

        $this->shouldThrow(TaxonNotFoundException::class)->during('getTaxon');
    }
}
