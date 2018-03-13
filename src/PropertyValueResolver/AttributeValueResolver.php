<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyValueResolver;

use Sylius\Component\Attribute\Model\AttributeValueInterface;

final class AttributeValueResolver implements AttributeValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(AttributeValueInterface $attributeValue)
    {
        $value = $attributeValue->getValue();

        if (AttributeValueInterface::STORAGE_JSON === $attributeValue->getType()) {
            return json_encode($value);
        }

        if (AttributeValueInterface::STORAGE_TEXT === $attributeValue->getType()) {
            return strtolower(str_replace([' ', '-'], '_', $value));
        }

        return $value;
    }
}
