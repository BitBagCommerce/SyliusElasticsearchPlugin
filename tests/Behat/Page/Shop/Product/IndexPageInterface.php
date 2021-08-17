<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
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

    public function paginate(int $page): void;

    /**
     * @param int $min
     * @param int $max
     */
    public function filterPrice(int $min, int $max): void;

    public function changeLimit(int $limit): void;
}
