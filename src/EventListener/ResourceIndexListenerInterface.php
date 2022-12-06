<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;

interface ResourceIndexListenerInterface
{
    public const GET_PARENT_METHOD_KEY = 'getParentMethod';

    public const MODEL_KEY = 'model';

    public const SERVICE_ID_KEY = 'serviceId';

    public function updateIndex(GenericEvent $event): void;
}
