<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Model;

class FacetsConfig
{
    public static function get(): array
    {
        return [
            'price' => [
                'type' => 'histogram',
                'label' => 'Price',
                'options' => [
                    'field' => 'price_web_us',
                    'interval' => 10000*100,
                    'min_doc_count' => 1
                ]
            ],
            'taxon' => [
                'type' => 'terms',
                'label' => 'Taxon',
                'options' => [
                    'field' => 'product_taxons.keyword'
                ]
            ]
        ];
    }
}
