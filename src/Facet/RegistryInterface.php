<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

interface RegistryInterface
{
    /**
     * @param string $facetId
     * @param FacetInterface $facet
     */
    public function addFacet(string $facetId, FacetInterface $facet): void;

    /**
     * @return FacetInterface[]
     */
    public function getFacets(): array;

    /**
     * @param string $facetId
     * @return FacetInterface
     * @throws FacetNotFoundException
     */
    public function getFacetById(string $facetId): FacetInterface;
}
