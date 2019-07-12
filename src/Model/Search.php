<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

use Pagerfanta\Pagerfanta;

class Search
{
    /**
     * @var Box|null
     */
    private $box;

    /**
     * @var array|null
     */
    private $facets;

    public function __construct()
    {
        $this->box = new Box();
    }

    /**
     * @return Box|null
     */
    public function getBox(): ?Box
    {
        return $this->box;
    }

    /**
     * @param Box|null $box
     */
    public function setBox(?Box $box): void
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
