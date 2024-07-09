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

    public function __get(string $facetId): array
    {
        if (!array_key_exists($facetId, $this->selectedBuckets)) {
            return [];
        }

        return $this->selectedBuckets[$facetId];
    }

    public function __set(string $facetId, string $selectedBuckets): void
    {
        $this->selectedBuckets[$facetId] = $selectedBuckets;
    }

    public function __isset(string $facetId)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function current(): mixed
    {
        return current($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function next(): void
    {
        next($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function key(): mixed
    {
        return key($this->selectedBuckets);
    }

    /**
     * @inheritdoc
     */
    public function valid(): bool
    {
        $key = key($this->selectedBuckets);

        return null !== $key;
    }

    /**
     * @inheritdoc
     */
    public function rewind(): void
    {
        reset($this->selectedBuckets);
    }
}
