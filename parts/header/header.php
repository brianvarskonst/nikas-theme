<?php declare(strict_types=1);

use Brianvarskonst\Nikas\Helper\Icon;
use Brianvarskonst\Nikas\Navigation\Header;
use Brianvarskonst\Nikas\Navigation\Walker\BootstrapWalker;
use Inpsyde\App\App;

$iconProvider = App::make(Icon::class); ?>

<header class="Header">
    <nav class="NavbarMain navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo get_bloginfo('url'); ?>">
                <?php echo strtoupper(get_bloginfo('name')); ?>
            </a>

            <span class="navbar-text HeaderDescription">
                <?php echo get_bloginfo('description'); ?>
            </span>

            <?php wp_nav_menu(
                [
                    'theme_location' => Header::ID,
                    'depth' => 1,
                    'container' => '',
                    'fallback_cb' => 'BootstrapWalker::fallback',
                    'walker' => new BootstrapWalker(),
                    'items_wrap' => '<ul class="NavbarMainNav navbar-nav ml-auto">%3$s</ul>',
                ]
            ); ?>

            <ul class="NavbarMainActions nav ml-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" data-offcanvas-more="true">
                        <span class="Icon">
                            <?php echo $iconProvider->get('icon/more'); ?>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" data-offcanvas-search="true">
                        <span class="Icon">
                            <?php echo $iconProvider->get('icon/search'); ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<?php get_template_part('/../parts/header/categories');
