<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Histogram;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class PriceFacet implements FacetInterface
{
    public const FACET_ID = 'price';

    /**
     * @var ConcatedNameResolverInterface
     */
    private $channelPricingNameResolver;
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;
    /**
     * @var int
     */
    private $interval;
    /**
     * @var MoneyFormatterInterface
     */
    private $moneyFormatter;
    /**
     * @var CurrencyContextInterface
     */
    private $currencyContext;
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function __construct(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        MoneyFormatterInterface $moneyFormatter,
        CurrencyContextInterface $currencyContext,
        LocaleContextInterface $localeContext,
        int $interval
    ) {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->channelContext = $channelContext;
        $this->moneyFormatter = $moneyFormatter;
        $this->currencyContext = $currencyContext;
        $this->localeContext = $localeContext;
        $this->interval = $interval;
    }

    public function getAggregation(): AbstractAggregation
    {
        $priceFieldName = $this->channelPricingNameResolver->resolvePropertyName(
            $this->channelContext->getChannel()->getCode()
        );
        $histogram = new Histogram(self::FACET_ID, $priceFieldName, $this->interval);
        $histogram->setMinimumDocumentCount(1);
        return $histogram;
    }

    public function getQuery(array $selectedBuckets): AbstractQuery
    {
        $priceFieldName = $this->channelPricingNameResolver->resolvePropertyName(
            $this->channelContext->getChannel()->getCode()
        );
        $query = new BoolQuery();
        foreach ($selectedBuckets as $selectedBucket) {
            $query->addShould(
                new Range($priceFieldName, ['gte' => $selectedBucket, 'lte' => $selectedBucket + $this->interval])
            );
        }
        return $query;
    }

    public function getBucketLabel(array $bucket): string
    {
        $from = $this->moneyFormatter->format(
            (int)$bucket['key'],
            $this->currencyContext->getCurrencyCode(),
            $this->localeContext->getLocaleCode()
        );
        $to = $this->moneyFormatter->format(
            (int)($bucket['key'] + $this->interval),
            $this->currencyContext->getCurrencyCode(),
            $this->localeContext->getLocaleCode()
        );
        return sprintf('%s - %s (%s)', $from, $to, $bucket['doc_count']);
    }

    public function getLabel(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin.ui.facet.price.label';
    }
}
