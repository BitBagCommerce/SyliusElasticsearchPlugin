<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SearchProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

final class SearchProductsSortDataHandlerSpec extends ObjectBehavior
{
    public function let(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
    ): void {
        $this->beConstructedWith(
            $channelPricingNameResolver,
            $channelContext,
            'sold_units',
            'created_at',
            'price'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SearchProductsSortDataHandler::class);
    }

    function it_is_a_shop_products_sort_data_handler(): void
    {
        $this->shouldHaveType(ShopProductsSortDataHandler::class);
    }

    function it_implements_sort_data_handler_interface(): void
    {
        $this->shouldHaveType(SortDataHandlerInterface::class);
    }

    function it_sorts_by_relevance_descending_by_default(): void
    {
        $this->retrieveData([])->shouldBeEqualTo([
            'sort' => [
                SearchProductsSortDataHandler::SCORE_FIELD => [
                    'order' => SortDataHandlerInterface::SORT_DESC_INDEX,
                ],
            ],
        ]);
    }

    function it_sorts_by_relevance_when_it_is_explicitly_requested(): void
    {
        $this->retrieveData([
            'order_by' => SearchProductsSortDataHandler::RELEVANCE,
            'sort' => SortDataHandlerInterface::SORT_ASC_INDEX,
        ])->shouldBeEqualTo([
            'sort' => [
                SearchProductsSortDataHandler::SCORE_FIELD => [
                    'order' => SortDataHandlerInterface::SORT_ASC_INDEX,
                ],
            ],
        ]);
    }

    function it_does_not_add_unmapped_type_to_the_relevance_score(): void
    {
        $sort = $this->retrieveData([])['sort'];

        $sort[SearchProductsSortDataHandler::SCORE_FIELD]->shouldNotHaveKey('unmapped_type');
    }

    function it_delegates_other_sorters_to_the_parent_handler(): void
    {
        $this->retrieveData([
            'order_by' => 'created_at',
            'sort' => SortDataHandlerInterface::SORT_DESC_INDEX,
        ])->shouldBeEqualTo([
            'sort' => [
                'created_at' => [
                    'order' => SortDataHandlerInterface::SORT_DESC_INDEX,
                    'unmapped_type' => 'keyword',
                ],
            ],
        ]);
    }

    function it_still_resolves_the_price_field_per_channel(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ConcatedNameResolverInterface $channelPricingNameResolver,
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('WEB');
        $channelPricingNameResolver->resolvePropertyName('WEB')->willReturn('price_WEB');

        $this->retrieveData([
            'order_by' => 'price',
            'sort' => SortDataHandlerInterface::SORT_ASC_INDEX,
        ])->shouldBeEqualTo([
            'sort' => [
                'price_WEB' => [
                    'order' => SortDataHandlerInterface::SORT_ASC_INDEX,
                    'unmapped_type' => 'keyword',
                ],
            ],
        ]);
    }

    function it_throws_an_exception_for_an_invalid_sort_direction_on_relevance(): void
    {
        $this->shouldThrow(\UnexpectedValueException::class)->during('retrieveData', [
            [
                'order_by' => SearchProductsSortDataHandler::RELEVANCE,
                'sort' => 'sideways',
            ],
        ]);
    }

    function it_throws_an_exception_for_an_unsupported_sorter(): void
    {
        $this->shouldThrow(\UnexpectedValueException::class)->during('retrieveData', [
            ['order_by' => 'unsupported'],
        ]);
    }
}
