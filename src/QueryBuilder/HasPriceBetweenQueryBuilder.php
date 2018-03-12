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
use Elastica\Query\AbstractQuery;

final class HasPriceBetweenQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $channelPricingNameResolver;

    /**
     * @var string
     */
    private $pricePropertyPrefix;

    /**
     * @param ConcatedNameResolverInterface $channelPricingNameResolver
     * @param string $pricePropertyPrefix
     */
    public function __construct(ConcatedNameResolverInterface $channelPricingNameResolver, string $pricePropertyPrefix)
    {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->pricePropertyPrefix = $pricePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!$minPrice = $data[$this->pricePropertyPrefix] || !$maxPrice = $data['max_' . $this->pricePropertyPrefix]) {
            return null;
        }


    }
}
