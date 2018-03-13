<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @param RequestStack $requestStack
     * @param TaxonRepositoryInterface $taxonRepository
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        RequestStack $requestStack,
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext
    ) {
        $this->requestStack = $requestStack;
        $this->taxonRepository = $taxonRepository;
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxon(): TaxonInterface
    {
        $slug = $this->requestStack->getCurrentRequest()->get('slug');
        $localeCode = $this->localeContext->getLocaleCode();
        $taxon = $this->taxonRepository->findOneBySlug($slug, $localeCode);

        if (null === $slug || null === $taxon) {
            throw new TaxonNotFoundException();
        }

        return $taxon;
    }
}
