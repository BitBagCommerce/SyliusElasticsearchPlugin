<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Exception\TaxonNotFoundException;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TaxonContext implements TaxonContextInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private TaxonRepositoryInterface $taxonRepository,
        private LocaleContextInterface $localeContext
    ) {
    }

    public function getTaxon(): TaxonInterface
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $slug = $request->get('slug');
        $localeCode = $this->localeContext->getLocaleCode();
        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBySlug($slug, $localeCode);

        if (null === $slug || null === $taxon) {
            throw new TaxonNotFoundException();
        }

        return $taxon;
    }
}
