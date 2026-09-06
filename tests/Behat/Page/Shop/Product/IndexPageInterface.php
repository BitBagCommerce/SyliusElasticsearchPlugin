<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    public function searchByPhase(string $name): void;

    public function filter(): void;

    public function checkAttribute(string $attributeName, string $attributeValueName): void;

    public function checkOption(string $optionName, string $optionValueName): void;

    public function paginate(int $page): void;

    public function filterPrice(int $min, int $max): void;

    public function changeLimit(int $limit): void;
}
