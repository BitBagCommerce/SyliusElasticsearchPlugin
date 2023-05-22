<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /** @var AbstractBrowser */
    private $client;

    /** @var RouterInterface */
    private $router;

    public function __construct(AbstractBrowser $client, RouterInterface $router)
    {
        $this->client = $client;
        $this->router = $router;
    }

    /**
     * @When I search the products by :phrase phrase
     */
    public function iSearchTheProductsByPhrase(string $phrase): void
    {
        $this->client->request(
            'GET',
            $this->router->generate('bitbag_sylius_elasticsearch_plugin_shop_auto_complete_product_name', ['_locale' => 'en_US', 'query' => $phrase]),
            [],
            [],
            ['ACCEPT' => 'application/json']
        );
    }

    /**
     * @Then I should see :productsCount products
     */
    public function iShouldSeeProducts(int $productsCount): void
    {
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = \json_decode($response->getContent());

        Assert::count($content->items, $productsCount);
    }
}
