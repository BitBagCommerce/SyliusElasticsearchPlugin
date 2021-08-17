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
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\TaxonInterface;

final class ShopProductsSortDataHandlerSpec extends ObjectBehavior
{
    function let(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        TaxonContextInterface $taxonContext,
        ConcatedNameResolverInterface $taxonPositionNameResolver
    ): void {
        $this->beConstructedWith(
            $channelPricingNameResolver,
            $channelContext,
            $taxonContext,
            $taxonPositionNameResolver,
            'sold_units',
            'created_at',
            'price'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopProductsSortDataHandler::class);
    }

    function it_implements_sort_data_handler_interface(): void
    {
        $this->shouldHaveType(SortDataHandlerInterface::class);
    }

    function it_retrieves_data(
        TaxonContextInterface $taxonContext,
        TaxonInterface $taxon,
        ConcatedNameResolverInterface $taxonPositionNameResolver
    ): void
    {
        $taxonContext->getTaxon()->willReturn($taxon);
        $taxon->getCode()->willReturn('t_shirt');
        $taxonPositionNameResolver->resolvePropertyName('t_shirt')->willReturn('taxon_position_t_shirts');

        $this->retrieveData([])->shouldBeEqualTo([
            'sort' => [
                'taxon_position_t_shirts' => [
                    'order' => SortDataHandlerInterface::SORT_ASC_INDEX,
                    'unmapped_type' => 'keyword',
                ],
            ],
        ]);
    }
}
