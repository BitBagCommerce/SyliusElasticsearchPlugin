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

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class TaxonProductsSortDataHandler extends ShopProductsSortDataHandler
{
    public function __construct(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext,
        private TaxonContextInterface $taxonContext,
        private ConcatedNameResolverInterface $taxonPositionNameResolver,
        string $soldUnitsProperty,
        string $createdAtProperty,
        string $pricePropertyPrefix,
        private string $taxonPositionPropertyPrefix
    ) {
        parent::__construct(
            $channelPricingNameResolver,
            $channelContext,
            $soldUnitsProperty,
            $createdAtProperty,
            $pricePropertyPrefix
        );
    }

    protected function getDefaultOrderBy(): string
    {
        return $this->taxonPositionPropertyPrefix;
    }

    protected function getAvailableSorters(): array
    {
        return array_merge(parent::getAvailableSorters(), [$this->taxonPositionPropertyPrefix]);
    }

    protected function resolveOrderByProperty(string $orderBy): string
    {
        if ($this->taxonPositionPropertyPrefix === $orderBy) {
            /** @var string $taxonCode */
            $taxonCode = $this->taxonContext->getTaxon()->getCode();

            return $this->taxonPositionNameResolver->resolvePropertyName($taxonCode);
        }

        return parent::resolveOrderByProperty($orderBy);
    }
}
