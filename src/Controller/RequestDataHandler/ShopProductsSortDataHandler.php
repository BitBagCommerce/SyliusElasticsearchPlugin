<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class ShopProductsSortDataHandler implements SortDataHandlerInterface
{
    /** @var ConcatedNameResolverInterface */
    private $channelPricingNameResolver;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var TaxonContextInterface */
    private $taxonContext;

    /** @var ConcatedNameResolverInterface */
    private $taxonPositionNameResolver;

    /** @var string */
    private $soldUnitsProperty;

    /** @var string */
    private $createdAtProperty;

    /** @var string */
    private $pricePropertyPrefix;

    public function __construct(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        TaxonContextInterface $taxonContext,
        ConcatedNameResolverInterface $taxonPositionNameResolver,
        string $soldUnitsProperty,
        string $createdAtProperty,
        string $pricePropertyPrefix
    ) {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->channelContext = $channelContext;
        $this->taxonContext = $taxonContext;
        $this->taxonPositionNameResolver = $taxonPositionNameResolver;
        $this->soldUnitsProperty = $soldUnitsProperty;
        $this->createdAtProperty = $createdAtProperty;
        $this->pricePropertyPrefix = $pricePropertyPrefix;
    }

    public function retrieveData(array $requestData): array
    {
        $data = [];
        $positionSortingProperty = $this->getPositionSortingProperty();

        $orderBy = isset($requestData[self::ORDER_BY_INDEX]) ? $requestData[self::ORDER_BY_INDEX] : $positionSortingProperty;
        $sort = isset($requestData[self::SORT_INDEX]) ? $requestData[self::SORT_INDEX] : self::SORT_DESC_INDEX;

        $availableSorters = [$positionSortingProperty, $this->soldUnitsProperty, $this->createdAtProperty, $this->pricePropertyPrefix];
        $availableSorting = [self::SORT_ASC_INDEX, self::SORT_DESC_INDEX];

        if (!in_array($orderBy, $availableSorters) || !in_array($sort, $availableSorting)) {
            throw new \UnexpectedValueException();
        }

        if ($this->pricePropertyPrefix === $orderBy) {
            $channelCode = $this->channelContext->getChannel()->getCode();
            $orderBy = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        }

        $data['sort'] = [$orderBy => ['order' => strtolower($sort)]];

        return $data;
    }

    private function getPositionSortingProperty(): string
    {
        $taxonCode = $this->taxonContext->getTaxon()->getCode();

        return $this->taxonPositionNameResolver->resolvePropertyName($taxonCode);
    }
}
