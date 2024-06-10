<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepositoryInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductOptionRepositoryInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;

final class AutoDiscoverRegistry implements AutoDiscoverRegistryInterface
{
    /** @var FacetInterface[] */
    private array $facets = [];

    public function __construct(
        private bool $autoRegister,
        private ProductAttributeRepositoryInterface $productAttributeRepository,
        private ProductOptionRepositoryInterface $productOptionRepository,
        private ConcatedNameResolverInterface $attributeNameResolver,
        private ConcatedNameResolverInterface $optionNameResolver,
        private LocaleContextInterface $localeContext,
        private RegistryInterface $registry,
        private array $excludedAttributes = [],
        private array $excludedOptions = []
    ) {
    }

    public function autoRegister(): void
    {
        if (false === $this->autoRegister || [] !== $this->facets) {
            return;
        }

        $this->discoverAttributes();
        $this->discoverOptions();

        foreach ($this->facets as $facetId => $facet) {
            $this->registry->addFacet($facetId, $facet);
        }
    }

    private function discoverAttributes(): void
    {
        $attributes = $this->productAttributeRepository->findAllWithTranslations($this->localeContext->getLocaleCode());

        /** @var AttributeInterface $attribute */
        foreach ($attributes as $attribute) {
            $code = $attribute->getCode();

            if (in_array($code, $this->excludedAttributes, true)) {
                continue;
            }

            $this->facets[$code] = new AttributeFacet(
                $this->attributeNameResolver,
                $attribute,
                $this->localeContext,
            );
        }
    }

    private function discoverOptions(): void
    {
        $options = $this->productOptionRepository->findAllWithTranslations($this->localeContext->getLocaleCode());

        /** @var ProductOptionInterface $option */
        foreach ($options as $option) {
            $code = $option->getCode();

            if (in_array($code, $this->excludedOptions, true)) {
                continue;
            }

            $this->facets[$code] = new OptionFacet(
                $this->optionNameResolver,
                $option,
            );
        }
    }
}
