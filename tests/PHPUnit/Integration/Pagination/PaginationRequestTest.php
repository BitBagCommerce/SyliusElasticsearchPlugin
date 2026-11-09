<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Integration\Pagination;

use BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\TaxonProductsSearchAction;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\TaxonDataHandler;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinder;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use FOS\ElasticaBundle\Paginator\RawPaginatorAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Twig\Environment;

final class PaginationRequestTest extends TestCase
{
    private static ?Index $index = null;

    public static function setUpBeforeClass(): void
    {
        $url = $_SERVER['ELASTICSEARCH_URL'] ?? getenv('ELASTICSEARCH_URL');
        if (!is_string($url) || '' === $url) {
            self::markTestSkipped('Set ELASTICSEARCH_URL to run pagination request tests.');
        }

        $index = (new Client($url))->getIndex('bitbag_pagination_test_' . bin2hex(random_bytes(6)));
        $index->create(['mappings' => ['properties' => ['product_created_at' => ['type' => 'date']]]]);

        try {
            $documents = [];
            foreach (range(1, 20) as $id) {
                $documents[] = new Document((string) $id, ['product_created_at' => '2026-01-01T00:00:00Z']);
            }
            $index->addDocuments($documents);
            $index->refresh();
            self::$index = $index;
        } catch (\Throwable $exception) {
            $index->delete();

            throw $exception;
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$index?->delete();
        self::$index = null;
    }

    #[DataProvider('requests')]
    public function test_product_requests_have_controlled_pagination_responses(
        string $query,
        int $status,
        ?array $expectedResults,
    ): void {
        $dispatcher = new EventDispatcher();
        $kernel = new HttpKernel($dispatcher, new ControllerResolver());
        $dispatcher->addSubscriber(new ErrorListener(new ErrorController($kernel, null, new HtmlErrorRenderer(false))));
        $request = Request::create('/products?' . $query);
        $request->attributes->add(['_controller' => $this->controller(), 'template' => 'pagination.html.twig']);

        $response = $kernel->handle($request);

        self::assertSame($status, $response->getStatusCode());
        if (null !== $expectedResults) {
            self::assertSame($expectedResults, json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR));
        }
    }

    public static function requests(): iterable
    {
        $default = ['page' => 1, 'limit' => 9, 'results' => 9];
        yield 'default' => ['', 200, $default];
        yield 'second page' => ['limit=18&page=2', 200, ['page' => 2, 'limit' => 18, 'results' => 2]];
        yield 'last page' => ['limit=9&page=3', 200, ['page' => 3, 'limit' => 9, 'results' => 2]];
        yield 'oversized limit' => ['limit=100000001', 200, $default];
        yield 'zero limit' => ['limit=0', 200, $default];
        yield 'negative limit' => ['limit=-9', 200, $default];
        yield 'array limit' => ['limit[]=9', 200, $default];
        yield 'unconfigured limit' => ['limit=27', 200, $default];
        yield 'zero page' => ['page=0', 400, null];
        yield 'negative page' => ['page=-1', 400, null];
        yield 'invalid page' => ['page=invalid', 400, null];
        yield 'empty page' => ['page=', 400, null];
        yield 'array page' => ['page[]=2', 400, null];
        yield 'unavailable page' => ['page=4', 404, null];
        yield 'page beyond result window' => ['page=2000', 404, null];
    }

    private function controller(): TaxonProductsSearchAction
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $form->method('getData')->willReturn([]);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('createView')->willReturn(new FormView());
        $factory = $this->createMock(FormFactoryInterface::class);
        $factory->method('create')->willReturn($form);
        $listingData = $this->createMock(DataHandlerInterface::class);
        $listingData->method('retrieveData')->willReturn(['facets' => []]);
        $sorter = new ShopProductsSortDataHandler(
            $this->createMock(ConcatedNameResolverInterface::class),
            $this->createMock(ChannelContextInterface::class),
            'sold_units',
            'product_created_at',
            'price',
        );
        $dataHandler = new TaxonDataHandler($listingData, $sorter, new PaginationDataHandler(9, [9, 18, 36], 10000));
        $builder = $this->createMock(QueryBuilderInterface::class);
        $builder->method('buildQuery')->willReturn((new BoolQuery())->addMust(new MatchAll()));
        $finder = $this->createMock(PaginatedFinderInterface::class);
        $finder->method('findPaginated')->willReturnCallback(static fn ($query) => new Pagerfanta(
            new FantaPaginatorAdapter(new RawPaginatorAdapter(self::$index, $query)),
        ));
        $products = new ShopProductsFinder($builder, $finder, $this->createMock(RegistryInterface::class));
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturnCallback(static function (string $template, array $context): string {
            $products = $context['products'];
            $count = 0;
            foreach ($products->getCurrentPageResults() as $result) {
                ++$count;
            }

            return json_encode([
                'page' => $products->getCurrentPage(),
                'limit' => $products->getMaxPerPage(),
                'results' => $count,
            ], \JSON_THROW_ON_ERROR);
        });

        return new TaxonProductsSearchAction($factory, $dataHandler, $products, $twig);
    }
}
