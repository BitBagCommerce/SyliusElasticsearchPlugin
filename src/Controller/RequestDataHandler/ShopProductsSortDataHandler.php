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

class ShopProductsSortDataHandler implements SortDataHandlerInterface
{
    public function __construct(
        protected ConcatedNameResolverInterface $channelPricingNameResolver,
        protected ChannelContextInterface $channelContext,
        protected string $soldUnitsProperty,
        protected string $createdAtProperty,
        protected string $pricePropertyPrefix
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        $data = [];

        $orderBy = $requestData[self::ORDER_BY_INDEX] ?? $this->getDefaultOrderBy();
        $sort = $requestData[self::SORT_INDEX] ?? self::SORT_ASC_INDEX;

        $availableSorting = [self::SORT_ASC_INDEX, self::SORT_DESC_INDEX];

        if (!in_array($orderBy, $this->getAvailableSorters(), true) || !in_array($sort, $availableSorting, true)) {
            throw new UnexpectedValueException();
        }

        $orderBy = $this->resolveOrderByProperty($orderBy);

        $data['sort'] = [$orderBy => ['order' => strtolower($sort), 'unmapped_type' => 'keyword']];

        return $data;
    }

    protected function getDefaultOrderBy(): string
    {
        return $this->createdAtProperty;
    }

    /**
     * @return string[]
     */
    protected function getAvailableSorters(): array
    {
        return [$this->soldUnitsProperty, $this->createdAtProperty, $this->pricePropertyPrefix];
    }

    protected function resolveOrderByProperty(string $orderBy): string
    {
        if ($this->pricePropertyPrefix === $orderBy) {
            /** @var string $channelCode */
            $channelCode = $this->channelContext->getChannel()->getCode();
            $orderBy = $this->channelPricingNameResolver->resolvePropertyName($channelCode);
        }

        return $orderBy;
    }
}
