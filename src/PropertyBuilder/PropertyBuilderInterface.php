<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use FOS\ElasticaBundle\Event\PostTransformEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface PropertyBuilderInterface extends EventSubscriberInterface
{
    public function consumeEvent(PostTransformEvent $event): void;

    public function buildProperty(
        PostTransformEvent $event,
        string $class,
        callable $callback
    ): void;
}
