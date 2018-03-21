<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContext;
use BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContextInterface;
use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductAttributesContextSpec extends ObjectBehavior
{
    function let(
        TaxonContextInterface $taxonContext,
        ProductAttributesFinderInterface $attributesFinder
    ): void {
        $this->beConstructedWith($taxonContext, $attributesFinder);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductAttributesContext::class);
    }

    function it_implements_product_attributes_context_interface(): void
    {
        $this->shouldHaveType(ProductAttributesContextInterface::class);
    }

    function it_gets_attributes(
        TaxonContextInterface $taxonContext,
        ProductAttributesFinderInterface $attributesFinder,
        TaxonInterface $taxon
    ): void {
        $taxonContext->getTaxon()->willReturn($taxon);

        $attributesFinder->findByTaxon($taxon)->willReturn([]);

        $this->getAttributes()->shouldBeEqualTo([]);
    }
}
