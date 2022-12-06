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

use Iterator;

final class SearchFacets implements Iterator
{
    private array $selectedBuckets = [];

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
    public function valid(): bool
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
