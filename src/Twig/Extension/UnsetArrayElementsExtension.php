<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Twig\Extension;

final class UnsetArrayElementsExtension extends \Twig_Extension
{
    /**
     * @param array $elements
     * @param array $keys
     *
     * @return array
     */
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

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('unset_elements', [$this, 'unsetElements']),
        ];
    }
}
