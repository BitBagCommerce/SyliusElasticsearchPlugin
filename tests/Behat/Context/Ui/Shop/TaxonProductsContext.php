<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Then;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\TaxonProductsPageInterface;
use Webmozart\Assert\Assert;

final class TaxonProductsContext implements Context
{
    public function __construct(
        private TaxonProductsPageInterface $taxonProductsPage,
    ) {
    }

    #[Then('/^I should see the following options in the "([^"]*)" filter of the taxon products list:$/')]
    public function iShouldSeeTheFollowingOptionsInTheFilterOfTheTaxonProductsList(string $facetName, PyStringNode $options): void
    {
        Assert::same($this->taxonProductsPage->getFacetOptions($facetName), $options->getStrings());
    }
}
