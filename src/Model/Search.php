<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
