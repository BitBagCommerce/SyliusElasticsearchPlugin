<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use Symfony\Component\HttpFoundation\Request;

final class PaginationDataHandler implements DataHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function retrieveData(Request $request): array
    {
        $data = [];
        $page = 1;

        if ($currentPage = $request->query->get(self::PAGE_INDEX)) {
            $page = (int) $currentPage;
        }

        $data[self::PAGE_INDEX] = $page;

        return $data;
    }
}
