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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShopProductsFilterType extends AbstractFilterType
{
    /**
     * @var string
     */
    private $nameProperty;

    /**
     * @param string $nameProperty
     */
    public function __construct(string $nameProperty)
    {
        $this->nameProperty = $nameProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->nameProperty, TextType::class)
        ;
    }
}
