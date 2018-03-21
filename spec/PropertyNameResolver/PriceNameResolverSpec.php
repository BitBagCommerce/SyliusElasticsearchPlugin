<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolver;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use PhpSpec\ObjectBehavior;

final class PriceNameResolverSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('price');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PriceNameResolver::class);
    }

    function it_implements_price_name_resolver_interface(): void
    {
        $this->shouldHaveType(PriceNameResolverInterface::class);
    }

    function it_resolves_min_price_name(): void
    {
        $this->resolveMinPriceName()->shouldBeEqualTo('min_price');
    }

    function it_resolves_max_price_name(): void
    {
        $this->resolveMaxPriceName()->shouldBeEqualTo('max_price');
    }
}
