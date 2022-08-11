<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasChannelQueryBuilder;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class HasChannelQueryBuilderSpec extends ObjectBehavior
{
    function let(ChannelContextInterface $channelContext): void
    {
        $this->beConstructedWith($channelContext, 'channel_property');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasChannelQueryBuilder::class);
    }

    function it_implements_query_builder_interface(): void
    {
        $this->shouldHaveType(QueryBuilderInterface::class);
    }

    function it_builds_query(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel
    ): void {
        $channel->getCode()->willReturn('web');

        $channelContext->getChannel()->willReturn($channel);

        $this->buildQuery([])->shouldBeAnInstanceOf(Terms::class);
    }
}
