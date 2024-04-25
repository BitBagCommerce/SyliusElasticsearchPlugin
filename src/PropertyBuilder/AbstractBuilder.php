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
use Sylius\Component\Resource\Model\ToggleableInterface;

abstract class AbstractBuilder implements PropertyBuilderInterface
{
    public function buildProperty(
        PostTransformEvent $event,
        string $supportedModelClass,
        callable $callback
    ): void {
        $model = $event->getObject();

        if (!$model instanceof $supportedModelClass || ($model instanceof ToggleableInterface && !$model->isEnabled())) {
            return;
        }

        $document = $event->getDocument();

        $callback($model, $document);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostTransformEvent::class => 'consumeEvent',
        ];
    }
}
