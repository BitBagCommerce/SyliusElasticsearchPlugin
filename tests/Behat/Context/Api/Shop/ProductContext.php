<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductContext implements Context
{
    /** @var Client */
    private $client;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(Client $client, UrlGeneratorInterface $urlGenerator)
    {
        $this->client = $client;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @When I search the products by :phrase phrase
     */
    public function iSearchTheProductsByPhase(string $phrase): void
    {
        $this->client->request(
            'GET',
            $this->urlGenerator->generate('bitbag_sylius_elasticsearch_plugin_shop_auto_complete_product_name'),
            ['phrase' => $phrase],
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

        dump($response);
    }
}
