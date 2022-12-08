<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

final class Registry implements RegistryInterface
{
    /** @var FacetInterface[] */
    private array $facets = [];

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
