<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class SearchFacets implements \Iterator
{
    /** @var array[] */
    private $selectedBuckets = [];

    public function __get(string $facetId)
    {
        if (!array_key_exists($facetId, $this->selectedBuckets)) {
            return [];
        }
        return $this->selectedBuckets[$facetId];
    }

    public function __set(string $facetId, $selectedBuckets)
    {
        $this->selectedBuckets[$facetId] = $selectedBuckets;
    }

    public function __isset(string $facetId)
    {
        return isset($this->selectedBuckets[$facetId]);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->selectedBuckets);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->selectedBuckets);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->selectedBuckets);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        $key = key($this->selectedBuckets);
        return ($key !== null && $key !== false);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->selectedBuckets);
    }
}
