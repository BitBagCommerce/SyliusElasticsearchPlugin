<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Twig\Component;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
final class SearchFormComponent
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    #[ExposeInTemplate('search_form')]
    public function searchForm(): FormView
    {
        return $this->formFactory->create(SearchType::class)->createView();
    }
}
