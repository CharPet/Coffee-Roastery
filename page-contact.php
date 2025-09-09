<?php
/* Template Name: Contact */
get_header();
?>

<main>
    <h2 class="contact-title">Επικοινωνία</h2>

    <?php if (isset($_GET['submitted']) && $_GET['submitted'] === 'true') : ?>
    <div class="contact-success-message">
        <div class="success-icon">✓</div>
        <h3>Ευχαριστούμε για την επικοινωνία!</h3>
        <p>Το μήνυμά σας έχει σταλεί με επιτυχία. Θα επικοινωνήσουμε μαζί σας σύντομα.</p>
    </div>
    <?php else : ?>
    <p class="contact-text">Έχεις κάποια ερώτηση; <br> Συμπλήρωσε τη φόρμα επικοινωνίας</p>

    <!-- This form submits to the current page, to be handled by WordPress -->
    <form class="contact-form" method="POST" action="<?php echo esc_url(get_permalink()); ?>">

        <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
        <input type="hidden" name="contact_form_submit" value="1">

        <div class="input-container">
            <label for="name">Όνομα:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="input-container">
            <label for="lname">Επώνυμο:</label>
            <input type="text" id="lname" name="lname" required>
        </div>
        <div class="input-container">
            <label for="phone">Τηλέφωνο:</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        <div class="input-container">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="text-area-container">
            <label for="message">Μήνυμα:</label>
            <textarea id="message" name="message" rows="3" required></textarea>
        </div>
        <button type="submit" class="contact-send-button">Αποστολή</button>
    </form>
    <?php endif; ?>
</main>

<?php get_footer(); ?>