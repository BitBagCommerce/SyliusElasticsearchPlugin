<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Unit\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PaginationDataHandlerTest extends TestCase
{
    #[DataProvider('validPagination')]
    public function test_valid_pagination_is_preserved(array $input, array $expected): void
    {
        self::assertSame($expected, $this->handler()->retrieveData($input));
    }

    public static function validPagination(): iterable
    {
        yield 'default' => [[], ['page' => 1, 'limit' => 9]];
        foreach ([9, 18, 36] as $limit) {
            yield 'page size ' . $limit => [['page' => '2', 'limit' => (string) $limit], ['page' => 2, 'limit' => $limit]];
            $lastPage = intdiv(10000, $limit);
            yield 'last searchable page ' . $limit => [['page' => (string) $lastPage, 'limit' => $limit], ['page' => $lastPage, 'limit' => $limit]];
        }
    }

    #[DataProvider('invalidLimits')]
    public function test_invalid_limits_use_the_configured_default(mixed $limit): void
    {
        self::assertSame(['page' => 1, 'limit' => 9], $this->handler()->retrieveData(['limit' => $limit]));
    }

    public static function invalidLimits(): iterable
    {
        yield 'oversized limit' => ['100000001'];
        yield 'zero' => ['0'];
        yield 'negative' => ['-9'];
        yield 'unconfigured size' => ['27'];
        yield 'text' => ['invalid'];
        yield 'fraction' => ['9.5'];
        yield 'array' => [['9']];
        yield 'integer overflow' => ['999999999999999999999999'];
        yield 'empty' => [''];
    }

    #[DataProvider('invalidPages')]
    public function test_invalid_pages_are_bad_requests(mixed $page): void
    {
        $this->expectException(BadRequestHttpException::class);

        $this->handler()->retrieveData(['page' => $page]);
    }

    public static function invalidPages(): iterable
    {
        yield ['0'];
        yield ['-1'];
        yield ['invalid'];
        yield ['2invalid'];
        yield ['1.5'];
        yield [['2']];
        yield [''];
        yield ['999999999999999999999999'];
    }

    #[DataProvider('pageSizes')]
    public function test_pages_outside_the_elasticsearch_result_window_are_not_found(int $limit): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->handler()->retrieveData(['limit' => $limit, 'page' => intdiv(10000, $limit) + 1]);
    }

    public static function pageSizes(): iterable
    {
        foreach ([9, 18, 36] as $limit) {
            yield [$limit];
        }
    }

    public function test_the_result_window_is_configurable(): void
    {
        $handler = new PaginationDataHandler(9, [9, 18], 36);

        self::assertSame(['page' => 2, 'limit' => 18], $handler->retrieveData(['page' => '2', 'limit' => '18']));
        $this->expectException(NotFoundHttpException::class);

        $handler->retrieveData(['page' => '3', 'limit' => '18']);
    }

    public function test_the_existing_single_argument_constructor_keeps_custom_page_sizes(): void
    {
        $handler = new PaginationDataHandler(9);

        self::assertSame(['page' => 2, 'limit' => 27], $handler->retrieveData(['page' => '2', 'limit' => '27']));
        self::assertSame(['page' => 1, 'limit' => 9], $handler->retrieveData(['limit' => '100000001']));
    }

    #[DataProvider('invalidConfiguration')]
    public function test_the_default_limit_must_fit_the_result_window(int $defaultLimit, int $window): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PaginationDataHandler($defaultLimit, null, $window);
    }

    public static function invalidConfiguration(): iterable
    {
        yield [0, 10000];
        yield [-9, 10000];
        yield [9, 8];
        yield [9, 0];
    }

    private function handler(): PaginationDataHandler
    {
        return new PaginationDataHandler(9, [9, 18, 36], 10000);
    }
}
