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

            <!-- <h2>Μοναδικές ποικιλίες, αργό καβούρδισμα, η απόλυτη εμπειρία.</h2> -->

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
    <script>
    let slideIndex = 1;
    showSlides(slideIndex);

    // Auto-advance slides every 5 seconds
    setInterval(function() {
        slideIndex++;
        if (slideIndex > 3) slideIndex = 1;
        showSlides(slideIndex);
    }, 5000);

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let slides = document.getElementsByClassName("testimonial");
        let dots = document.getElementsByClassName("dot");

        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }

        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active");
        }

        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove("active");
        }

        if (slides[slideIndex - 1]) {
            slides[slideIndex - 1].classList.add("active");
        }
        if (dots[slideIndex - 1]) {
            dots[slideIndex - 1].classList.add("active");
        }
    }
    </script>
</main>
<?php get_footer(); ?>