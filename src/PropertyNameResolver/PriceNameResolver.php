<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

final class PriceNameResolver implements PriceNameResolverInterface
{
    /**
     * @var string
     */
    private $pricePropertyPrefix;

    /**
     * @param string $pricePropertyPrefix
     */
    public function __construct(string $pricePropertyPrefix)
    {
        $this->pricePropertyPrefix = $pricePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveMinPriceName(): string
    {
        return 'min_' . $this->pricePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveMaxPriceName(): string
    {
        return 'max_' . $this->pricePropertyPrefix;
    }
}
