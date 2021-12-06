<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
     * @inheritdoc
     */
    public function current()
    {
        return current($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        $key = key($this->selectedBuckets);

        return null !== $key && false !== $key;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->selectedBuckets);
    }
}
