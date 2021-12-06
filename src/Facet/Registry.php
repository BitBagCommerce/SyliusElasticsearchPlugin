<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

final class Registry implements RegistryInterface
{
    /** @var FacetInterface[] */
    private $facets = [];

    public function addFacet(string $facetId, FacetInterface $facet): void
    {
        $this->facets[$facetId] = $facet;
    }

    /**
     * @return FacetInterface[]
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    public function getFacetById(string $facetId): FacetInterface
    {
        if (!array_key_exists($facetId, $this->facets)) {
            throw new FacetNotFoundException(sprintf('Cannot find facet with ID "%s" in registry.', $facetId));
        }

        return $this->facets[$facetId];
    }
}
