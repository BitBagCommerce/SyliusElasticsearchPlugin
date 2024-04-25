<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\Product\IndexPageInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var IndexPageInterface */
    private $productIndexPage;

    public function __construct(IndexPageInterface $productIndexPage, SharedStorageInterface $sharedStorage)
    {
        $this->productIndexPage = $productIndexPage;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @When /^I go to the shop products page for ("([^"]+)" taxon)$/
     */
    public function iGoToTheShopProductsPageForTaxon(TaxonInterface $taxon): void
    {
        $this->productIndexPage->open(['slug' => $taxon->getSlug()]);

        $this->sharedStorage->set('current_taxon_page', $taxon);
    }

    /**
     * @When I search the products by :name phase
     */
    public function iSearchTheProductsByPhase(string $phase): void
    {
        $this->productIndexPage->searchByPhase($phase);
        $this->productIndexPage->filter();
    }

    /**
     * @When I filter products by :attributeValue :attributeName attribute
     */
    public function iFilterProductsByAttribute(string $attributeValue, string $attributeName): void
    {
        $this->productIndexPage->checkAttribute($attributeName, $attributeValue);
        $this->productIndexPage->filter();
    }

    /**
     * @Then /^I should see (\d+) products on (\d+) page$/
     * @Then /^I should see (\d+) products on the page$/
     */
    public function iShouldSeeProductsOnTheSecondPage(int $count, int $page = 1): void
    {
        if (1 < $page) {
            $this->productIndexPage->paginate($page);
        }

        Assert::same($this->productIndexPage->countProductsItems(), $count);
    }

    /**
     * @When I filter product price between :min and :max
     */
    public function iFilterProductPriceBetweenAnd(int $min, int $max): void
    {
        $this->productIndexPage->filterPrice($min, $max);
        $this->productIndexPage->filter();
    }

    /**
     * @When I filter products by :arg1 :arg2 option
     */
    public function iFilterProductsByOption(string $optionValue, string $optionName): void
    {
        $this->productIndexPage->checkOption($optionName, $optionValue);
        $this->productIndexPage->filter();
    }

    /**
     * @When I change the limit to :limit
     */
    public function iChangeTheLimitTo(int $limit): void
    {
        $this->productIndexPage->changeLimit($limit);
    }
}
