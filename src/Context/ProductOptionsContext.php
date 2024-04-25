<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinderInterface;

final class ProductOptionsContext implements ProductOptionsContextInterface
{
    private TaxonContextInterface $taxonContext;

    private ProductOptionsFinderInterface $optionsFinder;

    public function __construct(
        TaxonContextInterface $taxonContext,
        ProductOptionsFinderInterface $optionsFinder
    ) {
        $this->taxonContext = $taxonContext;
        $this->optionsFinder = $optionsFinder;
    }

    public function getOptions(): ?array
    {
        $taxon = $this->taxonContext->getTaxon();

        return $this->optionsFinder->findByTaxon($taxon);
    }
}
