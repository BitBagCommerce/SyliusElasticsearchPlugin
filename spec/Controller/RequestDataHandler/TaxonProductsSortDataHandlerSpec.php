<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\TaxonProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;

final class TaxonProductsSortDataHandlerSpec extends ObjectBehavior
{
    public function let(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        TaxonContextInterface $taxonContext,
        ConcatedNameResolverInterface $taxonPositionNameResolver,
    ): void {
        $this->beConstructedWith(
            $channelPricingNameResolver,
            $channelContext,
            $taxonContext,
            $taxonPositionNameResolver,
            'sold_units',
            'created_at',
            'price',
            'taxon_position'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonProductsSortDataHandler::class);
    }

    function it_is_a_shop_products_sort_data_handler(): void
    {
        $this->shouldHaveType(ShopProductsSortDataHandler::class);
    }

    function it_implements_sort_data_handler_interface(): void
    {
        $this->shouldHaveType(SortDataHandlerInterface::class);
    }

    function it_sorts_by_taxon_position_ascending_by_default(
        TaxonContextInterface $taxonContext,
        TaxonInterface $taxon,
        ConcatedNameResolverInterface $taxonPositionNameResolver,
    ): void {
        $taxonContext->getTaxon()->willReturn($taxon);
        $taxon->getCode()->willReturn('t_shirts');
        $taxonPositionNameResolver->resolvePropertyName('t_shirts')->willReturn('taxon_position_t_shirts');

        $this->retrieveData([])->shouldBeEqualTo([
            'sort' => [
                'taxon_position_t_shirts' => [
                    'order' => SortDataHandlerInterface::SORT_ASC_INDEX,
                    'unmapped_type' => 'keyword',
                ],
            ],
        ]);
    }

    function it_resolves_the_taxon_position_field_for_the_current_taxon(
        TaxonContextInterface $taxonContext,
        TaxonInterface $taxon,
        ConcatedNameResolverInterface $taxonPositionNameResolver,
    ): void {
        $taxonContext->getTaxon()->willReturn($taxon);
        $taxon->getCode()->willReturn('mugs');
        $taxonPositionNameResolver->resolvePropertyName('mugs')->willReturn('taxon_position_mugs');

        $this->retrieveData([
            'order_by' => 'taxon_position',
            'sort' => 'desc',
        ])->shouldBeEqualTo([
            'sort' => [
                'taxon_position_mugs' => [
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
            'sort' => 'asc',
        ])->shouldBeEqualTo([
            'sort' => [
                'price_WEB' => [
                    'order' => SortDataHandlerInterface::SORT_ASC_INDEX,
                    'unmapped_type' => 'keyword',
                ],
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
