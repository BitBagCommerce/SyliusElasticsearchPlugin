<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinderInterface;

final class ProductOptionsContext implements ProductOptionsContextInterface
{
    /** @var TaxonContextInterface */
    private $taxonContext;

    /** @var ProductOptionsFinderInterface */
    private $optionsFinder;

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
        $options = $this->optionsFinder->findByTaxon($taxon);

        return $options;
    }
}
