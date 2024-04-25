<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper;

class AttributesTypePercentMapper implements AttributesMapperCollectorInterface
{
    private const ATTRIBUTES_TYPE = 'percent';

    public function supports(string $type): bool
    {
        return self::ATTRIBUTES_TYPE === $type;
    }

    public function map(array $attributeValues): array
    {
        $choices = [];

        foreach ($attributeValues as $productAttributeValue) {
            $value = $productAttributeValue['value'];
            $choice = $value * 100;
            $choices[$choice] = $value;
        }

        return $choices;
    }
}
