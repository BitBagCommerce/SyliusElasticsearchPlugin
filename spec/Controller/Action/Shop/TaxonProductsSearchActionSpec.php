<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\TaxonProductsSearchAction;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;
use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TaxonProductsSearchActionSpec extends ObjectBehavior
{
    function let(
        FormFactoryInterface $formFactory,
        DataHandlerInterface $dataHandler,
        ShopProductsFinderInterface $finder,
        Environment $twig
    ): void {
        $this->beConstructedWith(
            $formFactory,
            $dataHandler,
            $finder,
            $twig
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonProductsSearchAction::class);
    }

    function it_renders_product_list(
        FormFactoryInterface $formFactory,
        FormInterface $form,
        DataHandlerInterface $dataHandler,
        Pagerfanta $pagerfanta,
        FormView $formView,
        Environment $twig,
        Response $response,
        ShopProductsFinderInterface $finder
    ): void {
        $form->getData()->willReturn([]);
        $form->isValid()->willReturn(true);
        $form->isSubmitted()->willReturn(true);
        $form->handleRequest(Argument::any())->willReturn($form);
        $form->createView()->willReturn($formView);

        $formFactory->create(ShopProductsFilterType::class)->willReturn($form);

        $request = new Request(query: ['slug' => null], attributes: ['template' => '@Template']);

        $dataHandler->retrieveData(['slug' => null])->willReturn(['taxon' => null]);

        $finder->find(['taxon' => null])->willReturn($pagerfanta);

        $twig->render('@Template', Argument::any())->shouldBeCalled();

        $this->__invoke($request);
    }
}
