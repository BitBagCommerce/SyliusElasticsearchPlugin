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

use Symfony\Component\Form\FormBuilderInterface;

final class ShopProductsFilterType extends AbstractFilterType
{
    /**
     * @var string
     */
    private $namePropertyPrefix;

    /**
     * @param string $namePropertyPrefix
     */
    public function __construct(string $namePropertyPrefix)
    {
        $this->namePropertyPrefix = $namePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
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
