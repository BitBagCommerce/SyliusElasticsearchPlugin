<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Block;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchBoxType;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

final class SearchFormEventListener
{
    /** @var string */
    private $template;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(string $template, FormFactoryInterface $formFactory, RouterInterface $router)
    {
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
                    'form' => $this->getForm()->createView()
                ]
            )
        );
        $block->setType('sonata.block.service.template');

        $event->addBlock($block);
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        if (!$this->form) {
            $this->form = $this->formFactory
                ->createBuilder(SearchBoxType::class, ['query' => ''])
                ->setAction($this->router->generate('bitbag_sylius_elasticsearch_plugin_shop_search'))
                ->getForm();
        }
        return $this->form;
    }
}
