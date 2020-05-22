<?php

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
