<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

final class SiteWideDataHandler implements DataHandlerInterface
{
    public function __construct(
        private DataHandlerInterface $sortDataHandler,
        private DataHandlerInterface $paginationDataHandler,
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        return array_merge(
            $this->sortDataHandler->retrieveData($requestData),
            $this->paginationDataHandler->retrieveData($requestData)
        );
    }
}
