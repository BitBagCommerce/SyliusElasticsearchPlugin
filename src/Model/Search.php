<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

use Pagerfanta\Pagerfanta;

class Search
{
    /**
     * @var SearchBox|null
     */
    private $box;

    /**
     * @var array|null
     */
    private $facets;

    public function __construct()
    {
        $this->box = new SearchBox();
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
     * @return array|null
     */
    public function getFacets(): ?array
    {
        return $this->facets;
    }

    /**
     * @param array|null $facets
     */
    public function setFacets(?array $facets): void
    {
        $this->facets = $facets;
    }
}
