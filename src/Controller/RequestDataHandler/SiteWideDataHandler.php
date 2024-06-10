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
