<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContextInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductAttributesMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductAttributesFilterType extends AbstractFilterType
{
    private ProductAttributesContextInterface $productAttributesContext;

    private ConcatedNameResolverInterface $attributeNameResolver;

    private ProductAttributesMapperInterface $productAttributesMapper;

    protected array $excludedAttributes;

    public function __construct(
        ProductAttributesContextInterface $productAttributesContext,
        ConcatedNameResolverInterface $attributeNameResolver,
        ProductAttributesMapperInterface $productAttributesMapper,
        array $excludedAttributes
    ) {
        $this->productAttributesContext = $productAttributesContext;
        $this->attributeNameResolver = $attributeNameResolver;
        $this->productAttributesMapper = $productAttributesMapper;
        $this->excludedAttributes = $excludedAttributes;
    }

    public function buildForm(FormBuilderInterface $builder, array $attributes): void
    {
        foreach ($this->productAttributesContext->getAttributes() as $productAttribute) {
            if (in_array($productAttribute->getCode(), $this->excludedAttributes)) {
                continue;
            }

            $name = $this->attributeNameResolver->resolvePropertyName($productAttribute->getCode());
            $choices = $this->productAttributesMapper->mapToChoices($productAttribute);
            $choices = array_unique($choices);

            $builder->add($name, ChoiceType::class, [
                'label' => $productAttribute->getName(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);
        }
    }
}
