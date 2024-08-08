<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use UnexpectedValueException;

final class ShopProductsSortDataHandler implements SortDataHandlerInterface
{
    public function __construct(
        private ConcatedNameResolverInterface $channelPricingNameResolver,
        private ChannelContextInterface $channelContext,
        private string $soldUnitsProperty,
        private string $createdAtProperty,
        private string $pricePropertyPrefix
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        $data = [];

        $orderBy = $requestData[self::ORDER_BY_INDEX] ?? $this->createdAtProperty;
        $sort = $requestData[self::SORT_INDEX] ?? self::SORT_ASC_INDEX;

        $availableSorters = [$this->soldUnitsProperty, $this->createdAtProperty, $this->pricePropertyPrefix];
        $availableSorting = [self::SORT_ASC_INDEX, self::SORT_DESC_INDEX];

        if (!in_array($orderBy, $availableSorters, true) || !in_array($sort, $availableSorting, true)) {
            throw new UnexpectedValueException();
        }

        if ($this->pricePropertyPrefix === $orderBy) {
            /** @var string $channelCode */
            $channelCode = $this->channelContext->getChannel()->getCode();
            $orderBy = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        }

        $data['sort'] = [$orderBy => ['order' => strtolower($sort), 'unmapped_type' => 'keyword']];

        return $data;
    }
}
