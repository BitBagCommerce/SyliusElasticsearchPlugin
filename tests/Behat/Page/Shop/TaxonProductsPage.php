<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Page\Shop;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class TaxonProductsPage extends SymfonyPage implements TaxonProductsPageInterface
{
    public function getRouteName(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin_shop_taxon_products';
    }

    public function getFacetOptions(string $facetName): array
    {
        return array_map(
            static fn (NodeElement $element): string => trim($element->getText()),
            $this->getElement('facet', ['%facet%' => str_replace(' ', '_', $facetName)])->findAll('css', '.form-check-label'),
        );
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'facet' => '#facets_%facet%',
        ]);
    }
}
