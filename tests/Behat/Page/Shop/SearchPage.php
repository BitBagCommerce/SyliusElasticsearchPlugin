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
        foreach ($this->getElement('search_results')->findAll('css', '[data-test-product-name]') as $productElement) {
            $results[] = trim($productElement->getText());
        }

        return $results;
    }

    protected function getDefinedElements(): array
    {
        return [
            'search_box_query' => '#bitbag_elasticsearch_search_query',
            'search_box_submit' => '#bitbag_elasticsearch_search_box_search',
            'search_results' => '#search_results',
            'search_facets_price' => '#bitbag_elasticsearch_search_facets_price',
            'search_facets_taxon' => '#bitbag_elasticsearch_search_facets_taxon',
            'search_facets_filter_button' => 'form[name="bitbag_elasticsearch_search"] button[type="submit"]',
            'search_facets_attribute_car_type' => '#bitbag_elasticsearch_search_facets_Car_Type',
            'search_facets_attribute_motorbike_type' => '#bitbag_elasticsearch_search_facets_Motorbike_Type',
            'search_facets_attribute_color' => '#bitbag_elasticsearch_search_facets_Color',
            'search_facets_option_supply' => '#bitbag_elasticsearch_search_facets_SUPPLY',
            'search_facets_option_SUPPLY' => '#bitbag_elasticsearch_search_facets_SUPPLY',
        ];
    }

    public function assertProductInSearchResults(ProductInterface $product): void
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

    public function assertPriceIntervals(array $expectedIntervals): void
    {
        $priceIntervals = array_map(
            static fn (NodeElement $element) => trim($element->getText()),
            $this->getElement('search_facets_price')->findAll('css', '.form-check-label')
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

    public function assertProductsCountInSearchResults(int $expectedCount): void
    {
        Assert::count($this->getSearchResults(), $expectedCount);
    }

    public function assertTaxonFacetOptions(array $expectedOptions): void
    {
        $options = array_map(
            static fn (NodeElement $element) => trim($element->getText()),
            $this->getElement('search_facets_taxon')->findAll('css', '.form-check-label')
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

    public function filterByFacet(string $facetName, string $label): void
    {
        $session = $this->getSession();
        $currentUrl = $session->getCurrentUrl();

        $parsedUrl = parse_url($currentUrl);
        $queryParams = [];

        if (!empty($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        $facetElement = $this->getElement("search_facets_{$facetName}");
        if (!$facetElement) {
            throw new \Exception("Facet '{$facetName}' not found.");
        }

        foreach ($facetElement->findAll('css', '.form-check-input') as $checkbox) {
            $labelElement = $checkbox->getParent()->find('css', 'label');

            if ($labelElement && preg_replace('/\s*\(\d+\)$/', '', trim($labelElement->getText())) === $label) {
                $queryParams['bitbag_elasticsearch_search']['facets'][$facetName][] = $checkbox->getAttribute('value');

                $newUrl = sprintf('%s://%s%s?%s',
                    $parsedUrl['scheme'] ?? 'http',
                    $parsedUrl['host'] ?? 'localhost',
                    $parsedUrl['path'] ?? '',
                    http_build_query($queryParams)
                );

                $session->visit($newUrl);
                return;
            }
        }

        throw new \Exception("Filter option '{$label}' not found in facet '{$facetName}'.");
    }

    public function filterByPriceInterval(string $intervalLabel): void
    {
        $this->filterByFacet('price', $intervalLabel);
    }

    public function filterByTaxon(string $taxon): void
    {
        $this->filterByFacet('taxon', $taxon);
    }

    public function assertAttributeFacetOptions(string $attributeFilterLabel, array $expectedOptions): void
    {
        $element = 'search_facets_attribute_' . strtolower(str_replace(' ', '_', $attributeFilterLabel));

        if (!$this->hasElement($element)) {
            throw new ExpectationException(
                sprintf("Element '%s' is not defined in `getDefinedElements()`", $element),
                $this->getSession()
            );
        }

        $options = array_map(
            static fn (NodeElement $element) => trim($element->getText()),
            $this->getElement($element)->findAll('css', '.form-check-label')
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

    public function assertOptionFacetOptions($optionFilterLabel, array $expectedOptions): void
    {
        $element = 'search_facets_option_' . strtoupper(str_replace(' ', '_', $optionFilterLabel));

        if (!$this->hasElement($element)) {
            throw new ExpectationException(
                sprintf("Element '%s' is not defined in `getDefinedElements()`", $element),
                $this->getSession()
            );
        }

        $options = array_map(
            static fn (NodeElement $element) => trim($element->getText()),
            $this->getElement($element)->findAll('css', '.form-check-label')
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
