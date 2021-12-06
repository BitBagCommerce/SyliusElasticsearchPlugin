<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Context\ProductOptionsContextInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductOptionsMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductOptionsFilterType extends AbstractFilterType
{
    /** @var ProductOptionsContextInterface */
    private $productOptionsContext;

    /** @var ConcatedNameResolverInterface */
    private $optionNameResolver;

    /** @var ProductOptionsMapperInterface */
    private $productOptionsMapper;

    public function __construct(
        ProductOptionsContextInterface $productOptionsContext,
        ConcatedNameResolverInterface $optionNameResolver,
        ProductOptionsMapperInterface $productOptionsMapper
    ) {
        $this->productOptionsContext = $productOptionsContext;
        $this->optionNameResolver = $optionNameResolver;
        $this->productOptionsMapper = $productOptionsMapper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->productOptionsContext->getOptions() as $productOption) {
            $name = $this->optionNameResolver->resolvePropertyName($productOption->getCode());
            $choices = $this->productOptionsMapper->mapToChoices($productOption);
            $choices = array_unique($choices);

            $builder->add($name, ChoiceType::class, [
                'label' => $productOption->getName(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);
        }
    }
}
