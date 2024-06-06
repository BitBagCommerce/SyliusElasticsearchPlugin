<?php

declare(strict_types=1);

namespace PHPUnit\Integration\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Tests\BitBag\SyliusElasticsearchPlugin\Integration\IntegrationTestCase;

final class ProductListingTest extends IntegrationTestCase
{
    public function test_it_finds_products_by_name(): void
    {
        $this->loadFixturesFromFiles(['Api/test_it_finds_products_by_name.yaml']);
        $this->populateElasticsearch();

        $this->client->request('GET', '/api/v2/shop/products/search?query=mug');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'test_it_finds_products_by_name', Response::HTTP_OK);
    }

    public function test_it_finds_products_by_name_and_facets(): void
    {
        $this->loadFixturesFromFiles(['Api/test_it_finds_products_by_name_and_facets.yaml']);
        $this->populateElasticsearch();

        $this->client->request('GET', '/api/v2/shop/products/search?query=mug&facets[color][]=red');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'test_it_finds_products_by_name_and_facets', Response::HTTP_OK);
    }

    private function populateElasticsearch(): void
    {
        $process = new Process(['tests/Application/bin/console', 'fos:elastica:populate']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Failed to populate Elasticsearch: ' . $process->getErrorOutput());
        }
    }
}
