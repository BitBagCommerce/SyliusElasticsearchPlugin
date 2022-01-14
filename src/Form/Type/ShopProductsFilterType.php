<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class ShopProductsFilterType extends AbstractFilterType
{
    /** @var string */
    private $namePropertyPrefix;

    public function __construct(string $namePropertyPrefix)
    {
        $this->namePropertyPrefix = $namePropertyPrefix;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->namePropertyPrefix, NameFilterType::class)
            ->add('options', ProductOptionsFilterType::class, ['required' => false, 'label' => false])
            ->add('attributes', ProductAttributesFilterType::class, ['required' => false, 'label' => false])
            ->add('price', PriceFilterType::class, ['required' => false, 'label' => false])
        ;
    }
}
