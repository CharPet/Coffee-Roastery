<?php
/* Template Name: Login/Sign Up */
get_header();
?>

<main class="login-main">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">Καλώς ήρθατε</h2>
                <p class="login-subtitle">Συνδεθείτε στον λογαριασμό σας</p>
            </div>

            <div class="login-form-wrapper">
                <?php
                // Show login form if user is not logged in
                if (!is_user_logged_in()) {
                    wp_login_form([
                        'label_username' => 'Email:',
                        'label_password' => 'Κωδικός:',
                        'label_remember' => 'Να με θυμάσαι',
                        'label_log_in'   => 'Είσοδος',
                        'remember'       => true,
                        'form_id'        => 'custom-loginform',
                    ]);
                    
                    // Registration and forgot password links
                    echo '<div class="login-links">';
                       echo '<p class="no-account">Δεν έχετε λογαριασμό; <a href="' . get_permalink(get_page_by_path('signup')) . '" class="register-link">Εγγραφείτε εδώ</a></p>';
                    echo '<p class="forgot-password"><a href="' . wp_lostpassword_url() . '" class="forgot-link">Ξεχάσατε τον κωδικό σας;</a></p>';
                    echo '</div>';
                } else {
                    echo '<div class="logged-in-message">';
                    echo '<div class="success-icon">✓</div>';
                    echo '<h3>Έχετε συνδεθεί!</h3>';
                    echo '<p>Καλώς ήρθατε πίσω, ' . wp_get_current_user()->display_name . '</p>';
                    echo '<a href="' . home_url() . '" class="btn-home">Επιστροφή στην αρχική</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>


    </div>
</main>

<?php get_footer(); ?>