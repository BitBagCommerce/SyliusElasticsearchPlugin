<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasAttributesQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\BoolQuery;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasAttributesQueryBuilderSpec extends ObjectBehavior
{
    function let(
        LocaleContextInterface $localeContext
    ): void {
        $this->beConstructedWith($localeContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasAttributesQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(LocaleContextInterface $localeContext): void
    {
        $localeContext->getLocaleCode()->willReturn('en');
        $this->buildQuery([
            'attribute_values' => ['XL', 'L'],
            'attribute' => 'size',
        ])->shouldBeAnInstanceOf(BoolQuery::class);
    }
}
