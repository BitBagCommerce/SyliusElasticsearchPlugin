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
use Behat\Step\When;
use Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\HomePageInterface;

class HomepageContext implements Context
{
    public function __construct(
        private HomePageInterface $homePage,
    ) {
    }

    #[When('/^I open the home page$/')]
    public function iOpenTheHomePage()
    {
        $this->homePage->open();
    }
}
