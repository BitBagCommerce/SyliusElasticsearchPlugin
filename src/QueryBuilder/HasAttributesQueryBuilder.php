<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesQueryBuilderCollectorInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepositoryInterface;
use Elastica\Query\AbstractQuery;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasAttributesQueryBuilder implements QueryBuilderInterface
{
    private $localeContext;

    /** @var ProductAttributeRepositoryInterface */
    private $productAttributeRepository;

    /** @var AttributesQueryBuilderCollectorInterface[] */
    private $attributeDriver;

    public function __construct(
        LocaleContextInterface $localeContext,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        iterable $attributeDriver
    ) {
        $this->localeContext = $localeContext;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->attributeDriver = $attributeDriver;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $attributeName = str_replace('attribute_', '', $data['attribute']);

        $type = $this->productAttributeRepository->getAttributeTypeByName($attributeName);

        foreach ($this->attributeDriver as $driver) {
            if ($driver->supports($type)) {
                return $driver->buildQuery($data, $this->localeContext->getLocaleCode());
            }
        }

        return null;
    }
}
