<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Inpsyde\App\Container;
use Inpsyde\App\Provider\BootedOnly;

class BackwardCompatibilityProvider extends BootedOnly
{

    public function boot(Container $container): bool
    {
        if (!function_exists('wp_body_open')) {
            // Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
            function wp_body_open() {
                do_action('wp_body_open');
            }
        }

        return true;
    }
}