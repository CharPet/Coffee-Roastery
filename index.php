<?php get_header(); ?>

<main>
    <section class="hero-section">
        <div class="hero-text">
            <h1>
                Ανακάλυψε τον αυθεντικό καφέ — <br />
                από τον κόκκο στην κούπα
            </h1>

            <a href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>" class="cta-button">
                Εξερεύνηστε τις ποικιλίες μας
            </a>

            <div class="testimonials-slideshow">
                <div class="testimonial-container">
                    <div class="testimonial active">
                        <p class="testimonial-text">"Ο καλύτερος καφές που έχω δοκιμάσει! Η ποιότητα είναι εξαιρετική
                            και η γεύση απαράμιλλη."</p>
                        <div class="testimonial-author">- Μαρία Παπαδοπούλου</div>
                    </div>
                    <div class="testimonial">
                        <p class="testimonial-text">"Μοναδικές ποικιλίες και αργό καβούρδισμα που κάνει τη διαφορά.
                            Συνιστώ ανεπιφύλακτα!"</p>
                        <div class="testimonial-author">- Γιάννης Κωνσταντίνου</div>
                    </div>
                    <div class="testimonial">
                        <p class="testimonial-text">"Από τον κόκκο στην κούπα, κάθε γουλιά είναι μια απόλυτη εμπειρία.
                            Απλά τέλειο!"</p>
                        <div class="testimonial-author">- Ελένη Νικολάου</div>
                    </div>
                </div>
                <div class="slideshow-dots">
                    <span class="dot active" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>
            </div>

            <video class="bg-video" autoplay muted loop playsinline>
                <source src="<?php echo get_template_directory_uri(); ?>/videos/background-small.mp4"
                    type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            <div class="hero-layout">
                <div class="sticky-discount">
                    <aside>
                        <a href="#" class="discount-link">
                            Το <span>Terra Coffee</span> άνοιξε! Λάβε <u>κουπόνι</u> έκπτωσης 10% με την 1η
                            σου παραγγελία!
                        </a>
                    </aside>
                </div>

                <div class="sticky-newsletter">
                    <aside class="newsletter-aside">
                        <form class="newsletter-form">
                            <label for="newsletter-email" class="newsletter-label">
                                Εγγραφείτε στο newsletter μας για νέα και προσφορές!
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