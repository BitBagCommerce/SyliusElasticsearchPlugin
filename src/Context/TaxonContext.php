<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Context;

use BitBag\SyliusElasticsearchPlugin\Exception\TaxonNotFoundException;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class TaxonContext implements TaxonContextInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(
        RequestStack $requestStack,
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext
    ) {
        $this->requestStack = $requestStack;
        $this->taxonRepository = $taxonRepository;
        $this->localeContext = $localeContext;
    }

    public function getTaxon(): TaxonInterface
    {
        $slug = $this->requestStack->getCurrentRequest()->get('slug');
        $localeCode = $this->localeContext->getLocaleCode();
        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBySlug($slug, $localeCode);

        if (null === $slug || null === $taxon) {
            throw new TaxonNotFoundException();
        }

        return $taxon;
    }
}
