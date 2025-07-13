<?php
/* Template Name: Contact */
get_header();
?>

<main>
    <h2 class="contact-title">Επικοινωνία</h2>
    <p class="contact-text">Έχεις κάποια ερώτηση; <br> Συμπλήρωσε τη φόρμα επικοινωνίας</p>
    <form class="contact-form" method="post" action="">
        <div>
            <label for="name">Όνομα:</label>
            <input type="text" id="name" name="name" required>

            <label for="lname">Επώνυμο:</label>
            <input type="text" id="lname" name="lname" required>
        </div>
        <div>
            <label for="phone">Τηλέφωνο:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <label for="message">Μήνυμα:</label>
        <textarea id="message" name="message" rows="3" required></textarea>

        <button type="submit">Αποστολή</button>
    </form>
</main>

<?php get_footer(); ?>