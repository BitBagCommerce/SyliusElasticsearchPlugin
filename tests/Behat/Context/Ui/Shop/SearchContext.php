<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Sylius\Component\Core\Model\ProductInterface;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\SearchPageInterface;

final class SearchContext implements Context
{
    /** @var SearchPageInterface */
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

    /**
     * @Then /^I should see the following intervals in the price filter:$/
     */
    public function iShouldSeeTheFollowingIntervalsInThePriceFilter(PyStringNode $intervals)
    {
        $this->searchPage->assertPriceIntervals($intervals->getStrings());
    }

    /**
     * @Given /^I should see (\d+) products in search results$/
     */
    public function iShouldSeeProductsInSearchResults(int $expectedCount)
    {
        $this->searchPage->assertProductsCountInSearchResults($expectedCount);
    }

    /**
     * @Then /^I should see the following options in the taxon filter:$/
     */
    public function iShouldSeeTheFollowingOptionsInTheTaxonFilter(PyStringNode $options)
    {
        $this->searchPage->assertTaxonFacetOptions($options->getStrings());
    }

    /**
     * @Given /^I filter by price interval "([^"]*)"$/
     */
    public function iFilterByPriceInterval(string $intervalLabel)
    {
        $this->searchPage->filterByPriceInterval($intervalLabel);
    }

    /**
     * @Given /^I filter by taxon "([^"]*)"$/
     */
    public function iFilterByTaxon(string $taxon)
    {
        $this->searchPage->filterByTaxon($taxon);
    }

    /**
     * @Then /^I should see the following options in the "([^"]*)" attribute filter:$/
     */
    public function iShouldSeeTheFollowingOptionsInTheAttributeFilter(
        string $attributeFilterLabel,
        PyStringNode $options
    ) {
        $this->searchPage->assertAttributeFacetOptions($attributeFilterLabel, $options->getStrings());
    }

    /**
     * @Then /^I should see the following options in the "([^"]*)" option filter:$/
     */
    public function iShouldSeeTheFollowingOptionsInTheOptionFilter($optionFilterLabel, PyStringNode $options)
    {
        $this->searchPage->assertOptionFacetOptions($optionFilterLabel, $options->getStrings());
    }
}
