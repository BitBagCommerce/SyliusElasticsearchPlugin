<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

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
