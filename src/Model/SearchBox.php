<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class SearchBox
{
    /**
     * @var string|null
     */
    private $query;

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
}
