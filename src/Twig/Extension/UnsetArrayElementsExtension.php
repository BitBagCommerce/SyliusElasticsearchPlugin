<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
