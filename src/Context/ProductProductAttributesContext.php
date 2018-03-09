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

use BitBag\SyliusElasticsearchPlugin\Finder\AttributesFinderInterface;

final class ProductProductAttributesContext implements ProductAttributesContextInterface
{
    /**
     * @var TaxonContextInterface
     */
    private $taxonContext;

    /**
     * @var AttributesFinderInterface
     */
    private $attributesFinder;

    /**
     * @param TaxonContextInterface $taxonContext
     * @param AttributesFinderInterface $attributesFinder
     */
    public function __construct(
        TaxonContextInterface $taxonContext,
        AttributesFinderInterface $attributesFinder
    )
    {
        $this->taxonContext = $taxonContext;
        $this->attributesFinder = $attributesFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): ?array
    {
        $taxon = $this->taxonContext->getTaxon();
        $attributes = $this->attributesFinder->findByTaxon($taxon);

        return $attributes;
    }
}
