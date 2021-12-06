<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ExpectationException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

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
            'search_box_query' => '#bitbag_elasticsearch_search_box_query',
            'search_box_submit' => '#bitbag_elasticsearch_search_box_search',
            'search_results' => '#search_results',
            'search_facets_price' => '#bitbag_elasticsearch_search_facets_price',
            'search_facets_taxon' => '#bitbag_elasticsearch_search_facets_taxon',
            'search_facets_filter_button' => '#filters-vertical form button[type="submit"]',
            'search_facets_attribute_car_type' => '#bitbag_elasticsearch_search_facets_attribute_car_type',
            'search_facets_attribute_motorbike_type' => '#bitbag_elasticsearch_search_facets_attribute_motorbike_type',
            'search_facets_attribute_color' => '#bitbag_elasticsearch_search_facets_attribute_color',
            'search_facets_option_supply' => '#bitbag_elasticsearch_search_facets_option_supply',
        ];
    }

    public function assertProductInSearchResults(ProductInterface $product)
    {
        $results = $this->getSearchResults();
        foreach ($results as $result) {
            if (false !== strpos($result, $product->getName())) {
                return;
            }
        }

        throw new ExpectationException(
            sprintf('Cannot find a product named "%s" in the search results', $product->getName()),
            $this->getSession()
        );
    }

    public function assertPriceIntervals(array $expectedIntervals)
    {
        $priceIntervals = array_map(
            function (NodeElement $element) {
                return trim($element->getText());
            },
            $this->getElement('search_facets_price')->findAll('css', '.field')
        );
        Assert::eq(
            $priceIntervals,
            $expectedIntervals,
            sprintf(
                "Expected intervals are:\n%s\nGot:\n%s",
                print_r($expectedIntervals, true),
                print_r($priceIntervals, true)
            )
        );
    }

    public function assertProductsCountInSearchResults(int $expectedCount)
    {
        Assert::count($this->getSearchResults(), $expectedCount);
    }

    public function assertTaxonFacetOptions(array $expectedOptions)
    {
        $options = array_map(
            function (NodeElement $element) {
                return trim($element->getText());
            },
            $this->getElement('search_facets_taxon')->findAll('css', '.field')
        );
        Assert::eq(
            $options,
            $expectedOptions,
            sprintf(
                "Expected taxon facet options are:\n%s\nGot:\n%s",
                print_r($expectedOptions, true),
                print_r($options, true)
            )
        );
    }

    public function filterByPriceInterval(string $intervalLabel)
    {
        $this->getElement('search_facets_price')->findField($intervalLabel)->check();
        $this->getElement('search_facets_filter_button')->click();
    }

    public function filterByTaxon(string $taxon)
    {
        $this->getElement('search_facets_taxon')->findField($taxon)->check();
        $this->getElement('search_facets_filter_button')->click();
    }

    public function assertAttributeFacetOptions(string $attributeFilterLabel, array $expectedOptions)
    {
        $element = 'search_facets_attribute_' . strtolower(str_replace(' ', '_', $attributeFilterLabel));
        $options = array_map(
            function (NodeElement $element) {
                return $element->getText();
            },
            $this->getElement($element)->findAll('css', '.field')
        );
        Assert::eq(
            $options,
            $expectedOptions,
            sprintf(
                "Expected \"%s\" attribute facet options are:\n%s\nGot:\n%s",
                $attributeFilterLabel,
                print_r($expectedOptions, true),
                print_r($options, true)
            )
        );
    }

    public function assertOptionFacetOptions($optionFilterLabel, array $expectedOptions)
    {
        $element = 'search_facets_option_' . strtolower(str_replace(' ', '_', $optionFilterLabel));
        $options = array_map(
            function (NodeElement $element) {
                return $element->getText();
            },
            $this->getElement($element)->findAll('css', '.field')
        );
        Assert::eq(
            $options,
            $expectedOptions,
            sprintf(
                "Expected \"%s\" option facet options are:\n%s\nGot:\n%s",
                $optionFilterLabel,
                print_r($expectedOptions, true),
                print_r($options, true)
            )
        );
    }
}
