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
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->selectedBuckets);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->selectedBuckets);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->selectedBuckets);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $key = key($this->selectedBuckets);

        return $key !== null && $key !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->selectedBuckets);
    }
}
