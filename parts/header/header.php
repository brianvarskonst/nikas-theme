<?php

declare(strict_types=1);

?>

<header class="Header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo get_bloginfo('url'); ?>">
                <?php echo get_bloginfo('name'); ?>
            </a>

            <span class="navbar-text HeaderDescription">
                <?php echo get_bloginfo('description'); ?>
            </span>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <?php get_template_part('parts/navigation/main-navigation'); ?>
                </div>
            </div>
        </div>
    </nav>
</header>
