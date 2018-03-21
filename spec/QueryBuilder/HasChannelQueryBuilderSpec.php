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
