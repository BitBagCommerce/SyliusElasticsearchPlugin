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
use Sylius\Component\Locale\Context\LocaleContextInterface;

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
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @param PriceNameResolverInterface $priceNameResolver
     * @param ConcatedNameResolverInterface $channelPricingNameResolver
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        PriceNameResolverInterface $priceNameResolver,
        ConcatedNameResolverInterface $channelPricingNameResolver,
        LocaleContextInterface $localeContext
    )
    {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->priceNameResolver = $priceNameResolver;
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!$minPrice = $data[$this->priceNameResolver->resolveMinPrice()] ||
            !$maxPrice = $data[$this->priceNameResolver->resolveMinPrice()]) {
            return null;
        }

        $propertyName = $this->channelPricingNameResolver->resolvePropertyName($this->localeContext->getLocaleCode());
        $rangeQuery = new Range($propertyName, ['min' => $minPrice, 'max' => $maxPrice]);

        return $rangeQuery;
    }
}
