<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListProductsAction
{
    /**
     * @var PaginatedFinderInterface
     */
    private $productFinder;

    /**
     * @var DataHandlerInterface
     */
    private $shopProductListDataHandler;

    /**
     * @var QueryBuilderInterface
     */
    private $shopProductsQueryBuilder;

    /**
     * @param PaginatedFinderInterface $productFinder
     * @param DataHandlerInterface $shopProductListDataHandler
     * @param QueryBuilderInterface $shopProductsQueryBuilder
     */
    public function __construct(
        PaginatedFinderInterface $productFinder,
        DataHandlerInterface $shopProductListDataHandler,
        QueryBuilderInterface $shopProductsQueryBuilder
    )
    {
        $this->productFinder = $productFinder;
        $this->shopProductListDataHandler = $shopProductListDataHandler;
        $this->shopProductsQueryBuilder = $shopProductsQueryBuilder;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $data = $this->shopProductListDataHandler->retrieveData($request);
        $query = $this->shopProductsQueryBuilder->buildQuery($data);
        $result = $this->productFinder->find($query);

        return new JsonResponse($result);
    }
}
