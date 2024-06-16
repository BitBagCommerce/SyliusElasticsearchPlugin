<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Twig\ContextProvider;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchType;
use Sylius\Bundle\UiBundle\ContextProvider\ContextProviderInterface;
use Sylius\Bundle\UiBundle\Registry\TemplateBlock;
use Symfony\Component\Form\FormFactoryInterface;

final class SearchFormProvider implements ContextProviderInterface
{
    private const EVENT_NAME = 'sylius.shop.layout.header.content';

    private const BLOCK_NAME = 'bitbag_es_search_form';

    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function provide(array $templateContext, TemplateBlock $templateBlock): array
    {
        $form = $this->formFactory->create(SearchType::class);
        $templateContext['form'] = $form->createView();

        return $templateContext;
    }

    public function supports(TemplateBlock $templateBlock): bool
    {
        return self::EVENT_NAME === $templateBlock->getEventName()
            && self::BLOCK_NAME === $templateBlock->getName();
    }
}
