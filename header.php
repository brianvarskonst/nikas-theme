<?php

declare(strict_types=1); ?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

    <script>
        document.documentElement.className =
            document.documentElement.className.replace('no-js', 'js');
    </script>
</head>
<body <?php body_class(); ?>>

<?php

wp_body_open();

get_template_part('parts/header/header');

?>


<main class="content">