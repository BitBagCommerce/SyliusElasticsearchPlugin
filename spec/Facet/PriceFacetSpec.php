<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\PriceFacet;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\Histogram;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\Channel;

final class PriceFacetSpec extends ObjectBehavior
{
    private $interval = 1000000;

    function let(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        MoneyFormatterInterface $moneyFormatter,
        ShopperContextInterface $shopperContext
    ): void {
        $channel = new Channel();
        $channel->setCode('web_us');
        $shopperContext->getChannel()->willReturn($channel);
        $this->beConstructedWith(
            $channelPricingNameResolver,
            $moneyFormatter,
            $shopperContext,
            $this->interval
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PriceFacet::class);
    }

    function it_implements_facet_interface(): void
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    function it_returns_histogram_aggregation_for_price_field(
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channelPricingNameResolver->resolvePropertyName('web_us')->shouldBeCalled()->willReturn('price_web_us');
        $expectedHistogram = new Histogram('price', 'price_web_us', $this->interval);
        $expectedHistogram->setMinimumDocumentCount(1);

        $this->getAggregation()->shouldBeLike($expectedHistogram);
    }

    function it_returns_bool_query_made_of_ranges_based_on_selected_histograms(
        ConcatedNameResolverInterface $channelPricingNameResolver
    ): void {
        $channelPricingNameResolver->resolvePropertyName('web_us')->shouldBeCalled()->willReturn('price_web_us');

        $selectedHistograms = [1000000, 4000000];
        $expectedQuery = new BoolQuery();
        $expectedQuery->addShould(new Range('price_web_us', ['gte' => 1000000, 'lte' => 2000000]));
        $expectedQuery->addShould(new Range('price_web_us', ['gte' => 4000000, 'lte' => 5000000]));
        $this->getQuery($selectedHistograms)->shouldBeLike($expectedQuery);
    }

    function it_returns_money_formatted_bucket_label(
        MoneyFormatterInterface $moneyFormatter,
        ShopperContextInterface $shopperContext
    ): void {
        $shopperContext->getCurrencyCode()->willReturn('USD');
        $shopperContext->getLocaleCode()->willReturn('en_US');
        $moneyFormatter->format(1000000, 'USD', 'en_US')->shouldBeCalled()->willReturn('$10,000.00');
        $moneyFormatter->format(2000000, 'USD', 'en_US')->shouldBeCalled()->willReturn('$20,000.00');

        $this->getBucketLabel(['key' => 1000000, 'doc_count' => 6])->shouldBe('$10,000.00 - $20,000.00 (6)');
    }
}
