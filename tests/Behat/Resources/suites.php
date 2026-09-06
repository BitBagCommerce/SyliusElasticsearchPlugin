<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

use Behat\Config\Config;

return (new Config())
    ->import([
        'suites/api/searching_products.php',
        'suites/ui/filtering_products.php',
        'suites/ui/site_wide_searching_products.php',
    ])
;
