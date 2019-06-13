<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class SearchPage extends SymfonyPage implements SearchPageInterface
{
    public function getRouteName(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin_shop_search';
    }

    public function searchPhrase(string $phrase): void
    {
        $this->getElement('search_box')->setValue($phrase);
        $this->getElement('search_button')->click();
    }

    public function getSearchResults(): array
    {
        $results = [];
        foreach ($this->getElement('search_results')->findAll('css', '.result') as $resultElement) {
            $results[] = [
                'name' => $resultElement->find('css', '.product-name')->getText(),
                'taxons' => $resultElement->find('css', '.product-taxons')->getText(),
            ];
        }
        return $results;
    }

    protected function getDefinedElements(): array
    {
        return ['search_box' => '#search_box', 'search_button' => '#search_button'];
    }

}
