<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

final class PaginationDataHandler implements PaginationDataHandlerInterface
{
    public function __construct(
        private int $defaultLimit
    ) {
    }

    public function retrieveData(array $requestData): array
    {
        $data = [];

        $this->resolvePage($requestData, $data);
        $this->resolveLimit($requestData, $data);

        return $data;
    }

    private function resolvePage(array $requestData, array &$data): void
    {
        $page = 1;

        if (isset($requestData[self::PAGE_INDEX])) {
            $page = (int) $requestData[self::PAGE_INDEX];
        }

        $data[self::PAGE_INDEX] = $page;
    }

    private function resolveLimit(array $requestData, array &$data): void
    {
        $limit = $this->defaultLimit;

        if (isset($requestData[self::LIMIT_INDEX])) {
            $limit = (int) $requestData[self::LIMIT_INDEX];
        }

        $data[self::LIMIT_INDEX] = $limit;
    }
}
