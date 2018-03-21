<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class ShopProductsSortDataHandlerSpec extends ObjectBehavior
{
    function let(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext
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
        $this->shouldHaveType(ShopProductsSortDataHandler::class);
    }

    function it_implements_sort_data_handler_interface(): void
    {
        $this->shouldHaveType(SortDataHandlerInterface::class);
    }

    function it_retrieves_data(): void
    {
        $this->retrieveData([])->shouldBeEqualTo([
            'sort' => [
                'sold_units' => [
                    'order' => SortDataHandlerInterface::SORT_DESC_INDEX,
                ],
            ],
        ]);
    }
}
