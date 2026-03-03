<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Extension;

use Sylius\Bundle\AdminBundle\Form\Type\ProductOptionType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductOptionTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('facetDisabled', CheckboxType::class, [
            'label' => 'bitbag_sylius_elasticsearch_plugin.admin.form.facet_disabled',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductOptionType::class];
    }
}
