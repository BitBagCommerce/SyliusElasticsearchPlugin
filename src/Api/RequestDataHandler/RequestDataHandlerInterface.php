<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\RequestDataHandler;

interface RequestDataHandlerInterface
{
    public function retrieveData(array $context): array;
}
