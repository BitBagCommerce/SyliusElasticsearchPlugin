<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
