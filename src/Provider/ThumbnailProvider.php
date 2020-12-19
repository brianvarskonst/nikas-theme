<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Theme\Thumbnail\PostThumbnailSize;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\Booted;

class ThumbnailProvider extends Booted
{
    public function register(Container $container): bool
    {
        $container->addService(
            PostThumbnailSize::class,
            static function (Container $container): PostThumbnailSize {
                return new PostThumbnailSize();
            }
        );

        return true;
    }

    public function boot(Container $container): bool
    {
        $thumbnails = [
            $container->get(PostThumbnailSize::class),
        ];

        foreach ($thumbnails as $thumbnail) {
            set_post_thumbnail_size(
                $thumbnail->width(),
                $thumbnail->height(),
                $thumbnail->isCropped()
            );
        }

        return true;
    }
}
