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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

final class PriceFilterType extends AbstractFilterType
{
    /** @var PriceNameResolverInterface */
    private $priceNameResolver;

    /** @var CurrencyContextInterface */
    private $currencyContext;

    public function __construct(PriceNameResolverInterface $priceNameResolver, CurrencyContextInterface $currencyContext)
    {
        $this->priceNameResolver = $priceNameResolver;
        $this->currencyContext = $currencyContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->priceNameResolver->resolveMinPriceName(), MoneyType::class, [
                'label' => 'bitbag_sylius_elasticsearch_plugin.ui.min_price',
                'required' => false,
                'currency' => $this->currencyContext->getCurrencyCode(),
                'constraints' => [
                    new Type([
                        'type' => 'numeric',
                        'message' => 'bitbag_sylius_elasticsearch_plugin.min_price_numeric',
                    ]),
                    new PositiveOrZero([
                        'message' => 'bitbag_sylius_elasticsearch_plugin.min_price_positive_or_zero',
                    ]),
                ],
            ])
            ->add($this->priceNameResolver->resolveMaxPriceName(), MoneyType::class, [
                'label' => 'bitbag_sylius_elasticsearch_plugin.ui.max_price',
                'required' => false,
                'currency' => $this->currencyContext->getCurrencyCode(),
                'constraints' => [
                    new Type([
                        'type' => 'numeric',
                        'message' => 'bitbag_sylius_elasticsearch_plugin.max_price_numeric',
                    ]),
                    new PositiveOrZero([
                        'message' => 'bitbag_sylius_elasticsearch_plugin.min_price_positive_or_zero',
                    ]),
                ],
            ])
        ;
    }
}
