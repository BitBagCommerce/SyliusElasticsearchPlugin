<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var ProductAttributesContextInterface */
    private $productAttributesContext;

    /** @var ConcatedNameResolverInterface */
    private $attributeNameResolver;

    /** @var ProductAttributesMapperInterface */
    private $productAttributesMapper;

    /** @var array */
    protected $excludedAttributes;

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
