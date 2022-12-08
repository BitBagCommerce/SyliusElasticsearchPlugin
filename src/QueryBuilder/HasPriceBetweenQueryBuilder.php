<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Range;
use NumberFormatter;
use Sylius\Bundle\MoneyBundle\Form\DataTransformer\SyliusMoneyTransformer;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;

final class HasPriceBetweenQueryBuilder implements QueryBuilderInterface
{
    private ConcatedNameResolverInterface $channelPricingNameResolver;

    private PriceNameResolverInterface $priceNameResolver;

    private ChannelContextInterface $channelContext;

    private CurrencyContextInterface $currencyContext;

    private CurrencyConverterInterface $currencyConverter;

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
        $dataMinPrice = $this->getDataByKey($data, $this->priceNameResolver->resolveMinPriceName());
        $dataMaxPrice = $this->getDataByKey($data, $this->priceNameResolver->resolveMaxPriceName());

        $minPrice = $dataMinPrice ? $this->resolveBasePrice($dataMinPrice) : null;
        $maxPrice = $dataMaxPrice ? $this->resolveBasePrice($dataMaxPrice) : null;

        $channelCode = $this->channelContext->getChannel()->getCode();
        $propertyName = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        $rangeQuery = new Range();

        $paramValue = $this->getQueryParamValue($minPrice, $maxPrice);

        if (null === $paramValue) {
            return null;
        }

        $rangeQuery->setParam($propertyName, $paramValue);

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
        $transformer = new SyliusMoneyTransformer(2, false, NumberFormatter::ROUND_HALFUP, 100);

        return $transformer->reverseTransform($price);
    }

    private function getDataByKey(array $data, ?string $key = null): ?string
    {
        return $data[$key] ?? null;
    }

    private function getQueryParamValue(?int $min, ?int $max): ?array
    {
        foreach (['gte' => $min, 'lte' => $max] as $key => $value) {
            if (null !== $value) {
                $paramValue[$key] = $value;
            }
        }

        return $paramValue ?? null;
    }
}
