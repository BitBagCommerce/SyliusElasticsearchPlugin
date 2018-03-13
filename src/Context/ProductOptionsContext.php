<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinderInterface;

final class ProductOptionsContext implements ProductOptionsContextInterface
{
    /**
     * @var TaxonContextInterface
     */
    private $taxonContext;

    /**
     * @var ProductOptionsFinderInterface
     */
    private $optionsFinder;

    /**
     * @param TaxonContextInterface $taxonContext
     * @param ProductOptionsFinderInterface $optionsFinder
     */
    public function __construct(
        TaxonContextInterface $taxonContext,
        ProductOptionsFinderInterface $optionsFinder
    ) {
        $this->taxonContext = $taxonContext;
        $this->optionsFinder = $optionsFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): ?array
    {
        $taxon = $this->taxonContext->getTaxon();
        $options = $this->optionsFinder->findByTaxon($taxon);

        return $options;
    }
}
