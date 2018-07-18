<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    /**
     * @param string $name
     */
    public function searchByPhase(string $name): void;

    public function filter(): void;

    /**
     * @param string $attributeName
     * @param string $attributeValueName
     */
    public function checkAttribute(string $attributeName, string $attributeValueName): void;

    /**
     * @param string $optionName
     * @param string $optionValueName
     */
    public function checkOption(string $optionName, string $optionValueName): void;

    /**
     * @param int $page
     */
    public function paginate(int $page): void;

    /**
     * @param int $min
     * @param int $max
     */
    public function filterPrice(int $min, int $max): void;

    /**
     * @param int $limit
     */
    public function changeLimit(int $limit): void;
}
