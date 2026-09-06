<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Service\Populate;

final class ElasticsearchContext implements Context
{
    public function __construct(
        private Populate $populate,
    ) {
    }

    #[Given('the data is populated to Elasticsearch')]
    public function theDataIsPopulatedToElasticsearch(): void
    {
        $this->populate->populateIndex();
    }
}
