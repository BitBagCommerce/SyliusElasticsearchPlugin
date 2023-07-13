<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Integration;

use ApiTestCase\JsonApiTestCase;

abstract class IntegrationTestCase extends JsonApiTestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);

        $this->dataFixturesPath = __DIR__ . '/DataFixtures/ORM';
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function tearDown(): void
    {
    }
}
