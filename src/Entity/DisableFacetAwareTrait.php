<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait DisableFacetAwareTrait
{
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    protected bool $facetDisabled = false;

    public function isFacetDisabled(): bool
    {
        return $this->facetDisabled;
    }

    public function setFacetDisabled(bool $facetDisabled): void
    {
        $this->facetDisabled = $facetDisabled;
    }
}
