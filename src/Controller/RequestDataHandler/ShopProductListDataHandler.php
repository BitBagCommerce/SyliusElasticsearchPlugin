<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Context\TaxonContextInterface;

final class ShopProductListDataHandler implements DataHandlerInterface
{
    public function __construct(
        private TaxonContextInterface $taxonContext,
        private string $namePropertyPrefix,
        private string $taxonsProperty,
        private string $optionPropertyPrefix
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        $data = [];
        $taxon = $this->taxonContext->getTaxon();

        $data[$this->namePropertyPrefix] = (string) $requestData[$this->namePropertyPrefix];
        $data[$this->taxonsProperty] = strtolower((string) $taxon->getCode());
        $data['taxon'] = $taxon;
        $data = array_merge(
            $data,
            $requestData['price'] ?? [],
            ['facets' => $requestData['facets'] ?? []],
        );

        $this->handleOptionsPrefixedProperty($requestData, $data);

        return $data;
    }

    private function handleOptionsPrefixedProperty(
        array $requestData,
        array &$data
    ): void {
        if (!isset($requestData['options'])) {
            return;
        }

        foreach ($requestData['options'] as $key => $value) {
            if (is_array($value) && 0 === strpos($key, $this->optionPropertyPrefix)) {
                $data[$key] = array_map(function (string $property): string {
                    return strtolower($property);
                }, $value);
            }
        }
    }
}
