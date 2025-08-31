<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


    <header>
        <!-- Mobile menu toggle button -->
        <div class="header-bar">
            <div class="top-bar">
                <div class="top-bar-menu">
                    <div class="top-bar-menu-left">
                        <button class="mobile-nav-toggle" aria-label="Toggle navigation menu">
                            <div class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </button>

                        <?php if (is_user_logged_in()): ?>
                        <div class="user-welcome">
                            Συνδεδεμένος ως, <?php echo esc_html(wp_get_current_user()->display_name); ?>!
                        </div>
                        <?php endif; ?>
                    </div>
                    <div id="basket-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/icons/basket_svgrepo.com.svg"
                            alt="Basket" />
                        <span id="basket-count">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-nav">
            <!-- Left Navigation -->
            <nav class="nav-left">
                <ul class="nav-links nav-links-left">
                    <li><a href="/">Αρχική</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>">Κατάλογος Προϊόντων</a>
                    </li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'history' ) ); ?>">Η Ιστορία μας</a></li>
                </ul>
            </nav>

            <!-- Logo Container -->
            <a href="/">
                <figure class="logo-container">
                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/icons/terra coffee logo2 1.webp"
                        alt="Coffee Company Logo" width="128" height="128" />
                    <div>
                        <figcaption>Terra Coffee</figcaption>
                        <figcaption>ΚΑΦΕΣ ΜΕ ΡΙΖΕΣ ΣΤΗ ΓΗ <br> — <br>ΚΑΦΕΚΟΠΤΕΙΟΝ </figcaption>
                    </div>
                </figure>
            </a>

            <!-- Right Navigation -->
            <nav class="nav-right">
                <ul class="nav-links nav-links-right">
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'news' ) ); ?>">Τα Νέα μας</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'contact' ) ); ?>">Επικοινωνία</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'login' ) ); ?>">Εγγραφή / Είσοδος</a></li>
                </ul>
            </nav>

            <!-- Mobile Navigation Menu (hidden by default) -->
            <nav class="mobile-nav">
                <ul class="nav-links mobile-nav-links">
                    <li><a href="/">Αρχική</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>">Κατάλογος Προϊόντων</a>
                    </li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'history' ) ); ?>">Η Ιστορία μας</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'news' ) ); ?>">Τα Νέα μας</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'contact' ) ); ?>">Επικοινωνία</a></li>
                    <li><a href="<?php echo get_permalink( get_page_by_path( 'login' ) ); ?>">Εγγραφή / Είσοδος</a></li>
                </ul>
            </nav>

            <!-- Basket modal (site-wide) -->
            <div id="basket-modal" class="basket-modal" style="display:none;" aria-hidden="true">
                <div class="basket-inner" role="dialog" aria-label="Το καλάθι σας">
                    <h3>Το καλάθι σας</h3>
                    <ul id="basket-list"></ul>
                    <div id="basket-total"></div>
                    <div class="basket-actions">
                        <button id="buy-btn-modal">Αγορά</button>
                        <button id="close-basket">Κλείσιμο</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script src="./js/basket.js"></script>

    <!-- Mobile Navigation JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.querySelector('.mobile-nav-toggle');
        const mobileNav = document.querySelector('.mobile-nav');
        const mobileNavLinks = document.querySelector('.mobile-nav-links');
        const hamburger = document.querySelector('.hamburger');

        // Create overlay if it doesn't exist
        let navOverlay = document.querySelector('.nav-overlay');
        if (!navOverlay) {
            navOverlay = document.createElement('div');
            navOverlay.className = 'nav-overlay';
            document.body.appendChild(navOverlay);
        }

        function toggleMobileMenu() {
            const isActive = mobileNav.classList.contains('active');

            if (isActive) {
                // Close menu
                mobileNav.classList.remove('active');
                navOverlay.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            } else {
                // Open menu
                mobileNav.classList.add('active');
                navOverlay.classList.add('active');
                hamburger.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Toggle menu on button click
        if (mobileToggle) {
            mobileToggle.addEventListener('click', toggleMobileMenu);
        }

        // Close menu on overlay click
        navOverlay.addEventListener('click', toggleMobileMenu);

        // Close menu on nav link click
        const navLinksItems = document.querySelectorAll('.mobile-nav-links a');
        navLinksItems.forEach(link => {
            link.addEventListener('click', function() {
                if (mobileNav.classList.contains('active')) {
                    toggleMobileMenu();
                }
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
                toggleMobileMenu();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024 && mobileNav.classList.contains('active')) {
                mobileNav.classList.remove('active');
                navOverlay.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    </script>
</body>