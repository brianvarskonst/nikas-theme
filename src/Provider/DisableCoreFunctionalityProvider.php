<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBootedOnly;

class DisableCoreFunctionalityProvider extends EarlyBootedOnly
{
    public function boot(Container $container): bool
    {
        /** Disable the emoji's */
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_generator');

        /* Disable the Admin Bar. */
        add_filter(
            'show_admin_bar',
            static function (): bool {
                return is_admin();
            }
        );

        remove_action(
            'wp_enqueue_scripts',
            'enqueue_block_assets'
        );

        return true;
    }
}
