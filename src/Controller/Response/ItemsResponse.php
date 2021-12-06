<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
