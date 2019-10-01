<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface SearchPageInterface extends PageInterface
{
    public function searchPhrase(string $phrase): void;

    public function getSearchResults(): array;

    public function assertProductInSearchResults(ProductInterface $product);

    public function assertPriceIntervals(array $expectedIntervals);

    public function assertProductsCountInSearchResults(int $expectedCount);

    public function assertTaxonFacetOptions(array $expectedOptions);

    public function filterByPriceInterval(string $intervalLabel);

    public function filterByTaxon(string $taxon);

    public function assertAttributeFacetOptions(string $attributeFilterLabel, array $expectedOptions);

    public function assertOptionFacetOptions($optionFilterLabel, array $expectedOptions);
}
