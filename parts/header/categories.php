<?php

declare(strict_types=1);

use Brianvarskonst\Nikas\Category\Menu\CategoryMenu;
use Inpsyde\App\App;

$categoriesMenu = App::make(CategoryMenu::class); ?>

<nav class="CategoriesMenu" data-category-menu="true">
    <div class="CategoriesMenuTitle">
        <?php esc_html_e('Categories', 'nikas'); ?>
    </div>

    <div class="CategoriesMenuContainer">
        <?php echo $categoriesMenu->render(); ?>
    </div>

    <div class="CategoriesMenuViewAll">
        <?php esc_html_e('View all', 'nikas'); ?>
    </div>
</nav>
