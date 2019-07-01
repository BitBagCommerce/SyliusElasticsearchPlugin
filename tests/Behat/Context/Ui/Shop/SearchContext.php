<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Component\Core\Model\ProductInterface;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\SearchPageInterface;

final class SearchContext implements Context
{
    /**
     * @var SearchPageInterface
     */
    private $searchPage;

    public function __construct(SearchPageInterface $searchPage)
    {
        $this->searchPage = $searchPage;
    }

    /**
     * @When /^I browse the search page$/
     */
    public function iBrowseTheSearchPage()
    {
        $this->searchPage->open();
    }

    /**
     * @When /^I search the products by "([^"]*)" phrase in the site\-wide search box$/
     */
    public function iSearchTheProductsByPhraseInTheSiteWideSearchBox(string $phrase)
    {
        $this->searchPage->searchPhrase($phrase);
    }

    /**
     * @Then /^I should see the (product "([^"]*)") in the search results$/
     */
    public function iShouldSeeTheProductNamedInTheSearchResults(ProductInterface $product)
    {
        $this->searchPage->assertProductInSearchResults($product);
    }
}
