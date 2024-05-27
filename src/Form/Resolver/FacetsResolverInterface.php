<?php

namespace BitBag\SyliusElasticsearchPlugin\Form\Resolver;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormEvent;

interface FacetsResolverInterface
{
    public function resolveFacets(FormEvent $event): Pagerfanta;

}
