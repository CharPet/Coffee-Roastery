<?php
/* Template Name: signup */
get_header();

// Check for a success flag in the URL to show the success message.
if (isset($_GET['registered']) && $_GET['registered'] === 'true') :
?>
<main class="signup-main">
    <div class="signup-container">
        <div class="signup-card">
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h3>Επιτυχής Εγγραφή!</h3>
                <p>Η εγγραφή σας ολοκληρώθηκε επιτυχώς! Καλώς ήρθατε!</p>
                <a href="<?php echo home_url(); ?>" class="btn-home">Επιστροφή στην αρχική</a>
            </div>
        </div>
    </div>
</main>
<?php
// Check if the user is already logged in.
elseif (is_user_logged_in()) :
?>
<main class="signup-main">
    <div class="signup-container">
        <div class="signup-card">
            <div class="logged-in-message">
                <div class="info-icon">ℹ</div>
                <h3>Έχετε ήδη λογαριασμό</h3>
                <p>Είστε ήδη συνδεδεμένος ως <?php echo esc_html(wp_get_current_user()->display_name); ?></p>
                <a href="<?php echo home_url(); ?>" class="btn-home">Επιστροφή στην αρχική</a>
            </div>
        </div>
    </div>
</main>
<?php
// Otherwise, show the registration form.
else :
    // Get any errors or old field values from the transients set by our function.
    $errors = get_transient('signup_errors');
    $fields = get_transient('signup_fields');
    // Delete the transients so they don't persist on refresh.
    delete_transient('signup_errors');
    delete_transient('signup_fields');
?>
<main class="signup-main">
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h2 class="signup-title">Εγγραφή</h2>
                <p class="signup-subtitle">Δημιουργήστε τον λογαριασμό σας στο Terra Coffee</p>
            </div>
            <div class="signup-form-wrapper">
                <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <div class="error-icon">⚠</div>
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                        <li><?php echo esc_html($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="post" id="signup-form" action="<?php echo esc_url(get_permalink()); ?>">
                    <?php wp_nonce_field('register_nonce', 'nonce'); ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Όνομα:</label>
                            <input type="text" id="first_name" name="first_name"
                                value="<?php echo esc_attr($fields['first_name'] ?? ''); ?>" required>
                            <span class="validation-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Επώνυμο:</label>
                            <input type="text" id="last_name" name="last_name"
                                value="<?php echo esc_attr($fields['last_name'] ?? ''); ?>" required>
                            <span class="validation-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo esc_attr($fields['email'] ?? ''); ?>" required>
                        <span class="validation-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="username">Όνομα Χρήστη:</label>
                        <input type="text" id="username" name="username"
                            value="<?php echo esc_attr($fields['username'] ?? ''); ?>" required>
                        <span class="validation-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Κωδικός:</label>
                            <input type="password" id="password" name="password" required>
                            <small class="form-help">Τουλάχιστον 6 χαρακτήρες</small>
                            <span class="validation-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Επαλήθευση Κωδικού:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <span class="validation-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="place">Τόπος:</label>
                            <input type="text" id="place" name="place"
                                value="<?php echo esc_attr($fields['place'] ?? ''); ?>" required>
                            <span class="validation-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="zip">Τ.Κ.:</label>
                            <input type="text" id="zip" name="zip" value="<?php echo esc_attr($fields['zip'] ?? ''); ?>"
                                required>
                            <span class="validation-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Διεύθυνση Παραλαβής:</label>
                        <input type="text" id="address" name="address"
                            value="<?php echo esc_attr($fields['address'] ?? ''); ?>" required>
                        <span class="validation-error"></span>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            <span class="terms-text">Συμφωνώ με τους <a href="#" class="terms-link">Όρους και
                                    Προϋποθέσεις</a></span>
                        </label>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter" <?php checked(isset($fields['newsletter'])); ?>>
                            <span class="checkmark"></span>
                            <span class="terms-text">Θέλω να λαμβάνω newsletter με προσφορές και νέα</span>
                        </label>
                    </div>

                    <button type="submit" name="signup_submit" class="signup-button" value="1">Εγγραφή</button>
                </form>

                <div class="signup-links">
                    <p class="have-account">Έχετε ήδη λογαριασμό;
                        <a href="<?php echo wp_login_url(get_permalink()); ?>"
                            class="login-link-in-registration">Συνδεθείτε εδώ</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
endif;
get_footer();
?>