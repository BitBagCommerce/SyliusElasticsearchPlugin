<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
    /**
     * @var ProductAttributesContextInterface
     */
    private $productAttributesContext;

    /**
     * @var ConcatedNameResolverInterface
     */
    private $attributeNameResolver;

    /**
     * @var ProductAttributesMapperInterface
     */
    private $productAttributesMapper;

    /**
     * @param ProductAttributesContextInterface $productAttributesContext
     * @param ConcatedNameResolverInterface $attributeNameResolver
     * @param ProductAttributesMapperInterface $productAttributesMapper
     */
    public function __construct(
        ProductAttributesContextInterface $productAttributesContext,
        ConcatedNameResolverInterface $attributeNameResolver,
        ProductAttributesMapperInterface $productAttributesMapper
    ) {
        $this->productAttributesContext = $productAttributesContext;
        $this->attributeNameResolver = $attributeNameResolver;
        $this->productAttributesMapper = $productAttributesMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $attributes): void
    {
        foreach ($this->productAttributesContext->getAttributes() as $productAttribute) {
            $name = $this->attributeNameResolver->resolvePropertyName($productAttribute->getCode());
            $choices = $this->productAttributesMapper->mapToChoices($productAttribute);

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
