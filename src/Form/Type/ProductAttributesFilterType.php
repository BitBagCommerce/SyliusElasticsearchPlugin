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

/** @deprecated  */
final class ProductAttributesFilterType extends AbstractFilterType
{
    public function __construct(
        private ProductAttributesContextInterface $productAttributesContext,
        private ConcatedNameResolverInterface $attributeNameResolver,
        private ProductAttributesMapperInterface $productAttributesMapper,
        private array $excludedAttributes
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $attributes): void
    {
        /** @var array $options */
        $options = $this->productAttributesContext->getAttributes();
        foreach ($options as $productAttribute) {
            if (in_array($productAttribute->getCode(), $this->excludedAttributes, true)) {
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
