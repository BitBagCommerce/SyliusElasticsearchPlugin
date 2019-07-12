<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

use Pagerfanta\Pagerfanta;

class Box
{
    /**
     * @var string|null
     */
    private $query;

    /**
     * @var Pagerfanta|null
     */
    private $results;

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     */
    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }

    /**
     * @return Pagerfanta|null
     */
    public function getResults(): ?Pagerfanta
    {
        return $this->results;
    }

    /**
     * @param Pagerfanta|null $results
     */
    public function setResults(?Pagerfanta $results): void
    {
        $this->results = $results;
    }
}
