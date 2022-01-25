<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class HasChannelQueryBuilder implements QueryBuilderInterface
{
    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var string */
    private $channelsProperty;

    public function __construct(ChannelContextInterface $channelContext, string $channelsProperty)
    {
        $this->channelContext = $channelContext;
        $this->channelsProperty = $channelsProperty;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $channelQuery = new Terms($this->channelsProperty);
        $channelQuery->setTerms([strtolower($this->channelContext->getChannel()->getCode())]);

        return $channelQuery;
    }
}
