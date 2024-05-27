<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

final class TaxonDataHandler implements DataHandlerInterface
{
    public function __construct(
        private DataHandlerInterface $shopProductListDataHandler,
        private SortDataHandlerInterface $shopProductsSortDataHandler,
        private PaginationDataHandlerInterface $paginationDataHandler
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        return array_merge(
            $this->shopProductListDataHandler->retrieveData($requestData),
            $this->shopProductsSortDataHandler->retrieveData($requestData),
            $this->paginationDataHandler->retrieveData($requestData)
        );
    }
}
