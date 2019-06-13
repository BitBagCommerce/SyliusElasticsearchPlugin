<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface SearchPageInterface extends PageInterface
{
    public function searchPhrase(string $phrase): void;

    public function getSearchResults(): array;
}
