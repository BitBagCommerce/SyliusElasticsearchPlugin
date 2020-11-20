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
use Sylius\Bundle\MoneyBundle\Form\DataTransformer\SyliusMoneyTransformer;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;

final class HasPriceBetweenQueryBuilder implements QueryBuilderInterface
{
    /** @var ConcatedNameResolverInterface */
    private $channelPricingNameResolver;

    /** @var PriceNameResolverInterface */
    private $priceNameResolver;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var CurrencyContextInterface */
    private $currencyContext;

    /** @var CurrencyConverterInterface */
    private $currencyConverter;

    public function __construct(
        PriceNameResolverInterface $priceNameResolver,
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        CurrencyContextInterface $currencyContext,
        CurrencyConverterInterface $currencyConverter
    ) {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->priceNameResolver = $priceNameResolver;
        $this->channelContext = $channelContext;
        $this->currencyContext = $currencyContext;
        $this->currencyConverter = $currencyConverter;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $dataMinPrice = $data[$this->priceNameResolver->resolveMinPriceName()];
        $dataMaxPrice = $data[$this->priceNameResolver->resolveMaxPriceName()];

        $minPrice = $dataMinPrice ? $this->resolveBasePrice($dataMinPrice) : 0;
        $maxPrice = $dataMaxPrice ? $this->resolveBasePrice($dataMaxPrice) : \PHP_INT_MAX;

        $channelCode = $this->channelContext->getChannel()->getCode();
        $propertyName = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        $rangeQuery = new Range();

        $rangeQuery->setParam($propertyName, [
            'gte' => $minPrice,
            'lte' => $maxPrice,
        ]);

        return $rangeQuery;
    }

    private function resolveBasePrice(string $price): int
    {
        $price = $this->convertFromString($price);
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $currentCurrencyCode = $this->currencyContext->getCurrencyCode();
        $channelBaseCurrencyCode = $channel->getBaseCurrency()->getCode();

        if ($currentCurrencyCode !== $channelBaseCurrencyCode) {
            $price = $this->currencyConverter->convert($price, $currentCurrencyCode, $channelBaseCurrencyCode);
        }

        return $price;
    }

    private function convertFromString(string $price): int
    {
        $transformer = new SyliusMoneyTransformer(2, false, SyliusMoneyTransformer::ROUND_HALF_UP, 100);
        return $transformer->reverseTransform($price);
    }
}
