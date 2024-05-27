<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder\AttributesQueryBuilderCollectorInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepositoryInterface;
use Elastica\Query\AbstractQuery;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasAttributesQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private LocaleContextInterface $localeContext,
        private ProductAttributeRepositoryInterface $productAttributeRepository,
        /** @var AttributesQueryBuilderCollectorInterface[] */
        private iterable $attributeDriver
    ) {
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
