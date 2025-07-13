<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="basket-icon">
        <img src="<?php echo get_template_directory_uri(); ?>/icons/basket_svgrepo.com.svg" alt="Basket" />
        <span id="basket-count">0</span>
    </div>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="/">Αρχική</a></li>
                <li><a href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>">Κατάλογος Προϊόντων</a>
                </li>
                <li><a href="<?php echo get_permalink( get_page_by_path( 'history' ) ); ?>">Η Ιστορία μας</a></li>
            </ul>
        </nav>
        <figure class="logo-container">
            <img class="logo" src="<?php echo get_template_directory_uri(); ?>/icons/terra coffee logo2 1.webp"
                alt="Coffee Company Logo" width="128" height="128" />
            <div>
                <figcaption>Terra Coffee</figcaption>
                <figcaption>ΚΑΦΕΣ ΜΕ ΡΙΖΕΣ ΣΤΗ ΓΗ <br> — <br>ΚΑΦΕΚΟΠΤΕΙΟΝ </figcaption>
                <!-- <figcaption>ΚΑΦΕΚΟΠΤΕΙΟΝ</figcaption> -->

            </div>
        </figure>

        <nav>
            <ul class="nav-links">
                <li><a href="<?php echo get_permalink( get_page_by_path( 'news' ) ); ?>">Τα Νέα μας</a></li>
                <li><a href="<?php echo get_permalink( get_page_by_path( 'contact' ) ); ?>">Επικοινωνία</a></li>
                <li><a href="<?php echo get_permalink( get_page_by_path( 'login' ) ); ?>">Εγγραφή / Είσοδος</a></li>
            </ul>
        </nav>
    </header>
    <script src="./js/basket.js"></script>
</body>