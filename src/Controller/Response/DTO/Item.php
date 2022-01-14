<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO;

final class Item
{
    /** @var string */
    private $taxonName;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $slug;

    /** @var string */
    private $price;

    /** @var string */
    private $image;

    public function __construct(
        string $taxonName,
        string $name,
        ?string $description,
        string $slug,
        ?string $price,
        ?string $image
    ) {
        $this->taxonName = $taxonName;
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
        $this->price = $price;
        $this->image = $image;
    }

    public function taxonName(): string
    {
        return $this->taxonName;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function price(): ?string
    {
        return $this->price;
    }

    public function image(): ?string
    {
        return $this->image;
    }

    public function toArray(): array
    {
        return [
            'taxon_name' => $this->taxonName(),
            'name' => $this->name(),
            'description' => $this->description() ?: '',
            'slug' => $this->slug(),
            'price' => $this->price(),
            'image' => $this->image() ?: '',
        ];
    }
}
