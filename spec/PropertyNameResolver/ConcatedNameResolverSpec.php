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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use PhpSpec\ObjectBehavior;

final class ConcatedNameResolverSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('Book');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConcatedNameResolver::class);
    }

    function it_implements_concated_name_resolver_interface(): void
    {
        $this->shouldHaveType(ConcatedNameResolverInterface::class);
    }

    function it_resolves_property_name(): void
    {
        $this->resolvePropertyName('En')->shouldBeEqualTo('book_en');
    }
}
