<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Histogram;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;

final class PriceFacet implements FacetInterface
{
    public const FACET_ID = 'price';

    public function __construct(
        private ConcatedNameResolverInterface $channelPricingNameResolver,
        private MoneyFormatterInterface $moneyFormatter,
        private ShopperContextInterface $shopperContext,
        private int $interval
    ) {
    }

    public function getAggregation(): AbstractAggregation
    {
        $priceFieldName = $this->channelPricingNameResolver->resolvePropertyName(
            (string) $this->shopperContext->getChannel()->getCode()
        );
        $histogram = new Histogram(self::FACET_ID, $priceFieldName, $this->interval);
        $histogram->setMinimumDocumentCount(1);

        return $histogram;
    }

    public function getQuery(array $selectedBuckets): AbstractQuery
    {
        $priceFieldName = $this->channelPricingNameResolver->resolvePropertyName(
            (string) $this->shopperContext->getChannel()->getCode()
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
            (int) $bucket['key'],
            $this->shopperContext->getCurrencyCode(),
            $this->shopperContext->getLocaleCode()
        );
        $to = $this->moneyFormatter->format(
            (int) ($bucket['key'] + $this->interval),
            $this->shopperContext->getCurrencyCode(),
            $this->shopperContext->getLocaleCode()
        );

        return sprintf('%s - %s (%s)', $from, $to, $bucket['doc_count']);
    }

    public function getLabel(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin.ui.facet.price.label';
    }
}
