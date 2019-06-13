<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\SearchPageInterface;
use Webmozart\Assert\Assert;

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
     * @When /^I search the products by "([^"]*)" phrase in the site\-wide search box$/
     */
    public function iSearchTheProductsByPhraseInTheSiteWideSearchBox(string $phrase)
    {
        $this->searchPage->open();
        $this->searchPage->searchPhrase($phrase);
    }

    /**
     * @Then /^I should see the following products in the search results page:$/
     */
    public function iShouldSeeTheFollowingProductsInTheSearchResultsPage(TableNode $table)
    {
        $results = $this->searchPage->getSearchResults();
        foreach ($table as $i => $item) {
            Assert::contains($results[$i]['name'],$item['name']);
            Assert::contains($results[$i]['taxons'],$item['taxon']);
        }
    }
}
