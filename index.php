<?php get_header(); ?>

<main>
    <section class="hero-section">
        <div class="hero-text">
            <h1>
                Ανακάλυψε τον αυθεντικό καφέ — <br />
                από τον κόκκο στην κούπα
            </h1>

            <button class="cta-button"><a
                    href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>">Εξερεύνηστε τις ποικιλίες
                    μας</a></button>
            <h2>Μοναδικές ποικιλίες, αργό καβούρδισμα, η απόλυτη εμπειρία.</h2>
            <video class="bg-video" autoplay muted loop playsinline>
                <source src="<?php echo get_template_directory_uri(); ?>/videos/background-small.mp4"
                    type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            <div class="hero-layout">
                <div class="sticky-discount">
                    <aside>
                        <a href="#" class="discount-link">
                            Το Terra Coffee άνοιξε! Λάβε <u>κουπόνι</u> έκπτωσης 10% με την 1η
                            σου παραγγελία!
                        </a>
                    </aside>
                </div>

                <div class="sticky-newsletter">
                    <aside class="newsletter-aside">
                        <form class="newsletter-form">
                            <label for="newsletter-email" class="newsletter-label">
                                Εγγραφείτε στο newsletter μας για νέα & προσφορές!
                            </label>
                            <div class="newsletter-fields">
                                <input type="email" id="newsletter-email" class="newsletter-input"
                                    placeholder="Το email σας" required />
                                <button type="submit" class="newsletter-button">
                                    Εγγραφή
                                </button>
                            </div>
                        </form>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>