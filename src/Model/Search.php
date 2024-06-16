<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class Search
{
    private ?SearchBox $box;

    private array $facets = [];

    public function __construct()
    {
        $this->box = new SearchBox();
    }

    public function getBox(): ?SearchBox
    {
        return $this->box;
    }

    public function setBox(?SearchBox $box): void
    {
        $this->box = $box;
    }

    public function getFacets(): array
    {
        return $this->facets;
    }

    public function setFacets(array $facets): void
    {
        $this->facets = $facets;
    }
}
