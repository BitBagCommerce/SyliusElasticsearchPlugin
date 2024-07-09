<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO;

final class Item
{
    private string $taxonName;

    private string $name;

    private ?string $description;

    private string $slug;

    private ?string $price;

    private ?string $image;

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
            'description' => $this->description() ?? '',
            'slug' => $this->slug(),
            'price' => $this->price(),
            'image' => $this->image() ?? '',
        ];
    }
}
