<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class Search
{
    /** @var SearchBox|null */
    private $box;

    /** @var SearchFacets|null */
    private $facets;

    public function __construct()
    {
        $this->box = new SearchBox();
        $this->facets = new SearchFacets();
    }

    public function getBox(): ?SearchBox
    {
        return $this->box;
    }

    public function setBox(?SearchBox $box): void
    {
        $this->box = $box;
    }

    public function getFacets(): ?SearchFacets
    {
        return $this->facets;
    }

    public function setFacets(?SearchFacets $facets): void
    {
        $this->facets = $facets;
    }
}
