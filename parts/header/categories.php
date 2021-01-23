<?php

declare(strict_types=1);

use Brianvarskonst\Nikas\Category\Menu\CategoryMenu;
use Inpsyde\App\App;

$categoriesMenu = App::make(CategoryMenu::class); ?>

<nav class="CategoryMenu" data-category-menu="true">
    <div class="CategoryMenuTitle">
        <?php esc_html_e('Categories', 'nikas'); ?>
    </div>

    <div class="CategoryMenuContainer">
        <?php echo $categoriesMenu->render(); ?>
    </div>

    <div class="CategoryMenuViewAll">
        <?php esc_html_e('View all', 'nikas'); ?>
    </div>
</nav>
