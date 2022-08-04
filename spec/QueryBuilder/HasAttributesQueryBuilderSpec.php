<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesTypeTextQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasAttributesQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepository;
use Elastica\Query\BoolQuery;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasAttributesQueryBuilderSpec extends ObjectBehavior
{
    private iterable $attributeDriver;

    public function let(
        LocaleContextInterface $localeContext,
        ProductAttributeRepository $productAttributeRepository,
        AttributesTypeTextQueryBuilder $attributesTypeTextQueryBuilder
    ): void {
        $this->attributeDriver = new \ArrayIterator([$attributesTypeTextQueryBuilder->getWrappedObject()]);
        $this->beConstructedWith($localeContext, $productAttributeRepository, $this->attributeDriver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HasAttributesQueryBuilder::class);
    }

    public function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    public function it_builds_query(
        LocaleContextInterface $localeContext,
        ProductAttributeRepository $productAttributeRepository,
        AttributesTypeTextQueryBuilder $attributesTypeTextQueryBuilder,
        BoolQuery $boolQuery
    ): void {
        $data = [
            'attribute_values' => ['XL', 'L'],
            'attribute' => 'size',
        ];
        $attributeName = str_replace('attribute_', '', $data['attribute']);

        $productAttributeRepository->getAttributeTypeByName($attributeName)
            ->willReturn('select');

        $attributesTypeTextQueryBuilder->supports('select')
            ->willReturn(true);

        $localeContext->getLocaleCode()->willReturn('en');
        $attributesTypeTextQueryBuilder->buildQuery($data, 'en')
            ->willReturn($boolQuery);

        $this->buildQuery($data)
            ->shouldBeAnInstanceOf(BoolQuery::class);
    }
}
