<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class SearchBox
{
    /** @var string|null */
    private $query;

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }
}
