<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Block;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchType;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

final class SearchFormEventListener
{
    private string $template;

    private FormFactoryInterface $formFactory;

    private RouterInterface $router;

    private ?FormInterface $form = null;

    public function __construct(
        string $template,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->template = $template;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function onBlockEvent(BlockEvent $event): void
    {
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(
            array_replace(
                $event->getSettings(),
                [
                    'template' => $this->template,
                    'form' => $this->getForm()->createView(),
                ]
            )
        );
        $block->setType('sonata.block.service.template');

        $event->addBlock($block);
    }

    public function getForm(Search $search = null): FormInterface
    {
        if (!$this->form) {
            if (!$search) {
                $search = new Search();
            }
            $this->form = $this->formFactory
                ->create(
                    SearchType::class,
                    $search,
                    ['action' => $this->router->generate('bitbag_sylius_elasticsearch_plugin_shop_search')]
                );
        }

        return $this->form;
    }
}
