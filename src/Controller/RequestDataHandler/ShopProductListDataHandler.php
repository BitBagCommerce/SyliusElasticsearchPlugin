<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use Symfony\Component\HttpFoundation\Request;

final class ShopProductListDataHandler implements DataHandlerInterface
{
    /**
     * @var string
     */
    private $nameProperty;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @var string
     */
    private $attributePropertyPrefix;

    /**
     * @param string $nameProperty
     * @param string $taxonsProperty
     * @param string $optionPropertyPrefix
     * @param string $attributePropertyPrefix
     */
    public function __construct(
        string $nameProperty,
        string $taxonsProperty,
        string $optionPropertyPrefix,
        string $attributePropertyPrefix
    )
    {
        $this->nameProperty = $nameProperty;
        $this->taxonsProperty = $taxonsProperty;
        $this->optionPropertyPrefix = $optionPropertyPrefix;
        $this->attributePropertyPrefix = $attributePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveData(Request $request): array
    {
        $data = [];
        $data[$this->nameProperty] = $request->query->get($this->nameProperty);
        $data[$this->taxonsProperty] = $request->query->get($this->taxonsProperty);

        $this->handlePrefixedProperty($request, $this->optionPropertyPrefix, $data);
        $this->handlePrefixedProperty($request, $this->attributePropertyPrefix, $data);

        return $data;
    }

    /**
     * @param Request $request
     * @param string $propertyPrefix
     * @param array $data
     */
    private function handlePrefixedProperty(Request $request, string $propertyPrefix, array &$data): void
    {
        foreach ($request->query->all() as $key => $value) {
            if (0 === strpos($propertyPrefix, $key)) {
                $data[$key] = $value;
            }
        }
    }
}
