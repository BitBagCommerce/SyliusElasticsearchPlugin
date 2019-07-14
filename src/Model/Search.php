<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class Search
{
    /**
     * @var SearchBox|null
     */
    private $box;

    /**
     * @var SearchFacets|null
     */
    private $facets;

    public function __construct()
    {
        $this->box = new SearchBox();
        $this->facets = new SearchFacets();
    }

    /**
     * @return SearchBox|null
     */
    public function getBox(): ?SearchBox
    {
        return $this->box;
    }

    /**
     * @param SearchBox|null $box
     */
    public function setBox(?SearchBox $box): void
    {
        $this->box = $box;
    }

    /**
     * @return SearchFacets|null
     */
    public function getFacets(): ?SearchFacets
    {
        return $this->facets;
    }

    /**
     * @param SearchFacets|null $facets
     */
    public function setFacets(?SearchFacets $facets): void
    {
        $this->facets = $facets;
    }
}
