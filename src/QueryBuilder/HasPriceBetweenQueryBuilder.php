<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Range;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class HasPriceBetweenQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $channelPricingNameResolver;

    /**
     * @var PriceNameResolverInterface
     */
    private $priceNameResolver;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @param PriceNameResolverInterface $priceNameResolver
     * @param ConcatedNameResolverInterface $channelPricingNameResolver
     * @param ChannelContextInterface $channelContext
     */
    public function __construct(
        PriceNameResolverInterface $priceNameResolver,
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext
    ) {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->priceNameResolver = $priceNameResolver;
        $this->channelContext = $channelContext;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        $minPrice = $data[$this->priceNameResolver->resolveMinPriceName()];
        $maxPrice = $data[$this->priceNameResolver->resolveMaxPriceName()];

        if (!$minPrice || !$maxPrice) {
            return null;
        }

        $channelCode = $this->channelContext->getChannel()->getCode();
        $propertyName = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        $rangeQuery = new Range();

        $rangeQuery->setParam($propertyName, [
            'gte' => $this->getPriceFromString($minPrice),
            'lt' => $this->getPriceFromString($maxPrice),
        ]);

        return $rangeQuery;
    }

    private function getPriceFromString(string $price): int
    {
        return (int) round($price * 100, 2);
    }
}
