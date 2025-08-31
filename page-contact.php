<?php
/* Template Name: Contact */
get_header();
?>

<main>
    <h2 class="contact-title">Επικοινωνία</h2>
    <p class="contact-text">Έχεις κάποια ερώτηση; <br> Συμπλήρωσε τη φόρμα επικοινωνίας</p>
    <form class="contact-form" method="POST" action="">
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
        <button type="submit" class="button-primary">Αποστολή</button>
    </form>
</main>

<?php get_footer(); ?>