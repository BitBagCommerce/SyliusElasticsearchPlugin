<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

abstract class AbstractSearchAction
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
        protected DataHandlerInterface $dataHandler,
        protected ShopProductsFinderInterface $finder,
        protected Environment $twig
    ) {
    }

    protected function clearInvalidEntries(FormInterface $form, array $requestData): array
    {
        /** @var FormError $error */
        foreach ($form->getErrors(true) as $error) {
            /** @var FormInterface $errorOrigin */
            $errorOrigin = $error->getOrigin();

            /** @var FormInterface $parent */
            $parent = $errorOrigin->getParent();

            $path = ($parent->getPropertyPath() ?? '') . $errorOrigin->getPropertyPath();

            $keys = explode('][', trim($path, '[]'));

            $dataRef = &$requestData;
            foreach ($keys as $index => $key) {
                if (isset($dataRef[$key])) {
                    if ($index === count($keys) - 1) {
                        unset($dataRef[$key]);
                    } else {
                        $dataRef = &$dataRef[$key];
                    }
                }
            }
        }

        return $requestData;
    }
}
