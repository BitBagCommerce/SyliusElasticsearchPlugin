<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class UnsetArrayElementsExtension extends AbstractExtension
{
    public function unsetElements(array $elements, array $keys): array
    {
        foreach ($keys as $key) {
            if (!isset($elements[$key])) {
                continue;
            }

            unset($elements[$key]);
        }

        return $elements;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('unset_elements', [$this, 'unsetElements']),
        ];
    }
}
