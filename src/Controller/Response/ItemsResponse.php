<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Item;

final class ItemsResponse
{
    /** @var array|Item[] */
    private $items;

    private function __construct(array $itemsList)
    {
        $this->items = $itemsList;
    }

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public function addItem(DTO\Item $item): void
    {
        $this->items[] = $item;
    }

    public function all(): \Traversable
    {
        foreach ($this->items as $item) {
            yield $item->toArray();
        }
    }

    public function toArray(): array
    {
        return ['items' => iterator_to_array($this->all())];
    }
}
