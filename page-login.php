<?php
/* Template Name: Login/Sign Up */
get_header();
?>

<main class="login-main">
    <h2 class="login-title">Εγγραφή / Είσοδος</h2>
    <div class="login-form-wrapper">
        <?php
    // Show login form if user is not logged in
    if (!is_user_logged_in()) {
        wp_login_form([
            'label_username' => 'Όνομα χρήστη ή Email',
            'label_password' => 'Κωδικός',
            'label_remember' => 'Να με θυμάσαι',
            'label_log_in'   => 'Είσοδος',
            'remember'       => true,
        ]);
        // Registration link
        echo '<p>Δεν έχετε λογαριασμό; <a href="' . wp_registration_url() . '">Εγγραφείτε εδώ</a></p>';
    } else {
        echo '<p>Έχετε ήδη συνδεθεί.</p>';
    }
    ?>
    </div>
</main>

<?php get_footer(); ?>