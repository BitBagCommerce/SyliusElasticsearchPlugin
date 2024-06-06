<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;

final class RequestDataHandler implements RequestDataHandlerInterface
{
    public function __construct(
        private DataHandlerInterface $sortDataHandler,
        private DataHandlerInterface $paginationDataHandler,
    ) {
    }

    public function retrieveData(array $context): array
    {
        $requestData = $context['filters'] ?? [];

        return array_merge(
            $this->sortDataHandler->retrieveData($requestData),
            $this->paginationDataHandler->retrieveData($requestData),
            ['query' => $requestData['query'] ?? ''],
            ['facets' => $requestData['facets'] ?? []],
        );
    }
}
