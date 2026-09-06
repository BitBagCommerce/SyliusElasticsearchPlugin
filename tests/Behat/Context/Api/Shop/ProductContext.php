<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Behat\Step\Then;
use Behat\Step\When;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    public function __construct(
        private AbstractBrowser $client,
        private RouterInterface $router,
    ) {
    }

    #[When('I search the products by :phrase phrase')]
    public function iSearchTheProductsByPhrase(string $phrase): void
    {
        $this->client->request(
            'GET',
            $this->router->generate('bitbag_sylius_elasticsearch_plugin_shop_auto_complete_product_name', ['_locale' => 'en_US', 'query' => $phrase]),
            [],
            [],
            ['ACCEPT' => 'application/json'],
        );
    }

    #[Then('I should see :productsCount products')]
    public function iShouldSeeProducts(int $productsCount): void
    {
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = \json_decode($response->getContent());

        Assert::count($content->items, $productsCount);
    }
}
