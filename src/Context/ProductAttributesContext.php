<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinderInterface;

final class ProductAttributesContext implements ProductAttributesContextInterface
{
    /** @var TaxonContextInterface */
    private $taxonContext;

    /** @var ProductAttributesFinderInterface */
    private $attributesFinder;

    public function __construct(
        TaxonContextInterface $taxonContext,
        ProductAttributesFinderInterface $attributesFinder
    ) {
        $this->taxonContext = $taxonContext;
        $this->attributesFinder = $attributesFinder;
    }

    public function getAttributes(): ?array
    {
        $taxon = $this->taxonContext->getTaxon();
        $attributes = $this->attributesFinder->findByTaxon($taxon);

        return $attributes;
    }
}
