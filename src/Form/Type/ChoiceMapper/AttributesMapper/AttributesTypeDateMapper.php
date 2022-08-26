<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper;

final class AttributesTypeDateMapper implements AttributesMapperCollectorInterface
{
    private const ATTRIBUTES_TYPE = 'date';

    private const CHOICE_DATE_FORMAT = 'd-m-Y H:i';

    private const VALUE_DATE_FORMAT = 'Y-m-d H:i:s.sss';

    public function supports(string $type): bool
    {
        return self::ATTRIBUTES_TYPE === $type;
    }

    public function map(array $attributeValues): array
    {
        $choices = [];

        foreach ($attributeValues as $productAttributeValue) {
            $choice = $productAttributeValue['value']->format(self::CHOICE_DATE_FORMAT);
            $value = $productAttributeValue['value']->format(self::VALUE_DATE_FORMAT);
            $choices[$choice] = $value;
        }

        return $choices;
    }
}
