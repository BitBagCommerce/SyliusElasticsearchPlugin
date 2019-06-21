<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use Behat\Mink\Exception\ExpectationException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Sylius\Component\Core\Model\ProductInterface;

class SearchPage extends SymfonyPage implements SearchPageInterface
{
    public function getRouteName(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin_shop_search';
    }

    public function searchPhrase(string $phrase): void
    {
        $this->getElement('search_box_query')->setValue($phrase);
        $this->getElement('search_box_submit')->click();
    }

    public function getSearchResults(): array
    {
        $results = [];
        foreach ($this->getElement('search_results')->findAll('css', '.result') as $resultElement) {
            $results[] = $resultElement->getText();
        }
        return $results;
    }

    protected function getDefinedElements(): array
    {
        return [
            'search_box_query' => '#search_box_query',
            'search_box_submit' => '#search_box_search',
            'search_results' => '#search_results'
        ];
    }

    public function assertProductInSearchResults(ProductInterface $product)
    {
        $results = $this->getSearchResults();
        foreach ($results as $result) {
            if (strpos($result, $product->getName()) !== false) {
                return;
            }
        }
        throw new ExpectationException(
            sprintf('Cannot find a product named "%s" in the search results', $product->getName()), $this->getSession()
        );
    }
}
