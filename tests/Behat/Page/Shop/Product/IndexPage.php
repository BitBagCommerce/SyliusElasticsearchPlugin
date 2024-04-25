<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPage as BaseIndexPage;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    public function getRouteName(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin_shop_list_products';
    }

    public function searchByPhase(string $name): void
    {
        $this->getDocument()->fillField('name', $name);
    }

    public function filter(): void
    {
        $this->getElement('submit_filter')->press();
    }

    public function checkAttribute(string $attributeName, string $attributeValueName): void
    {
        $this->getElement('attributes_filter', ['%attributeName%' => $attributeName])->checkField($attributeValueName);
    }

    public function paginate(int $page): void
    {
        $this->getElement('pagination')->clickLink($page);
    }

    public function filterPrice(int $min, int $max): void
    {
        $this->getDocument()->fillField('Min price', $min);
        $this->getDocument()->fillField('Max price', $max);
    }

    public function changeLimit(int $limit): void
    {
        $this->getElement('limit')->clickLink($limit);
    }

    public function checkOption(string $optionName, string $optionValueName): void
    {
        $this->getElement('options_filter', ['%optionName%' => $optionName])->checkField($optionValueName);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'attributes_filter' => '#attributes label:contains("%attributeName%") ~ .dropdown',
            'options_filter' => '#options label:contains("%optionName%") ~ .dropdown',
            'limit' => '.ui.dropdown span:contains("Per page") ~ .menu',
            'submit_filter' => '#filters-vertical button[type="submit"]',
            'pagination' => '.pagination',
        ]);
    }
}
