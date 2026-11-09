<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PaginationDataHandler implements PaginationDataHandlerInterface
{
    public function __construct(
        private int $defaultLimit,
        private ?array $availableLimits = null,
        private int $maxResultWindow = 10000,
    ) {
        if (1 > $defaultLimit || $defaultLimit > $maxResultWindow) {
            throw new \InvalidArgumentException('The default product page size must fit within the result window.');
        }
    }

    public function retrieveData(array $requestData): array
    {
        $limit = filter_var($requestData[self::LIMIT_INDEX] ?? $this->defaultLimit, \FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => $this->maxResultWindow],
        ]);
        if (false === $limit || (null !== $this->availableLimits && !in_array($limit, $this->availableLimits, true))) {
            $limit = $this->defaultLimit;
        }

        $page = filter_var($requestData[self::PAGE_INDEX] ?? 1, \FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if (false === $page) {
            throw new BadRequestHttpException('Invalid product page.');
        }

        if ($page > intdiv($this->maxResultWindow, $limit)) {
            throw new NotFoundHttpException('Product page not found.');
        }

        return [self::PAGE_INDEX => $page, self::LIMIT_INDEX => $limit];
    }
}
