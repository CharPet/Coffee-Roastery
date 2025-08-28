<?php
/* Template Name: signup */
get_header();

// Enable debugging
// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', false);

// Database functions integrated directly in the template
function saveUserRegistrationData($user_id, $user_data) {
    global $wpdb;
    $table = $wpdb->prefix . 'user_registrations';
    
    // Debug table existence
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
    if (!$table_exists) {
        error_log("Table $table does not exist!");
        return false;
    }

    // Debug log the data being inserted
    error_log('Attempting to insert user data: ' . print_r([
        'user_id' => $user_id,
        'username' => $user_data['username'],
        'table' => $table
    ], true));

    $result = $wpdb->insert(
        $table,
        array(
            'user_id' => $user_id,
            'username' => $user_data['username'],
            'email' => $user_data['email'],
            'first_name' => $user_data['first_name'],
            'last_name' => $user_data['last_name'],
            'place' => $user_data['place'],
            'zip' => $user_data['zip'],
            'address' => $user_data['address'],
            'newsletter_subscribed' => isset($user_data['newsletter']) ? 1 : 0
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
    );

    if ($result === false) {
        // Log the actual MySQL error
        error_log('Database error details: ' . $wpdb->last_error);
        error_log('Last SQL query: ' . $wpdb->last_query);
        return false;
    }
    return true;
}

// Handle form submission
if (isset($_POST['signup_submit']) && $_POST['signup_submit']) {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $place = sanitize_text_field($_POST['place']);
    $zip = sanitize_text_field($_POST['zip']);
    $address = sanitize_text_field($_POST['address']);
    
    $errors = array();
    
    // Validation
    if (empty($username)) {
        $errors[] = 'Το όνομα χρήστη είναι υποχρεωτικό.';
    } elseif (username_exists($username)) {
        $errors[] = 'Το όνομα χρήστη υπάρχει ήδη.';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Παρακαλώ εισάγετε έγκυρο email.';
    } elseif (email_exists($email)) {
        $errors[] = 'Το email υπάρχει ήδη.';
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Ο κωδικός πρέπει να έχει τουλάχιστον 6 χαρακτήρες.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Οι κωδικοί δεν ταιριάζουν.';
    }
    
    if (empty($first_name)) {
        $errors[] = 'Το όνομα είναι υποχρεωτικό.';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Το επώνυμο είναι υποχρεωτικό.';
    }
    
    if (empty($place)) {
        $errors[] = 'Ο τόπος είναι υποχρεωτικός.';
    }
    
    if (empty($zip)) {
        $errors[] = 'Ο ταχυδρομικός κώδικας είναι υποχρεωτικός.';
    }
    
    if (empty($address)) {
        $errors[] = 'Η διεύθυνση είναι υποχρεωτική.';
    }
    
    // If no errors, create user
    if (empty($errors)) {
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => $first_name . ' ' . $last_name,
        );
        
        $user_id = wp_insert_user($userdata);
        
        if (!is_wp_error($user_id)) {
            // Save additional user data to custom table
            $registration_data = array(
                'username' => $username,
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'place' => $place,
                'zip' => $zip,
                'address' => $address,
                'newsletter' => isset($_POST['newsletter'])
            );
            
            $db_saved = saveUserRegistrationData($user_id, $registration_data);
            
            if (!$db_saved) {
                error_log('Failed to save user registration data for user_id: ' . $user_id);
                $errors[] = 'Database error during registration.';
            } else {
                // Auto login after registration
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                
                $success_message = 'Η εγγραφή σας ολοκληρώθηκε επιτυχώς! Καλώς ήρθατε!';
            }
        } else {
            $errors[] = 'Σφάλμα κατά την εγγραφή: ' . $user_id->get_error_message();
        }
    }
}
?>

<main class="signup-main">
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h2 class="signup-title">Εγγραφή</h2>
                <p class="signup-subtitle">Δημιουργήστε τον λογαριασμό σας στο Terra Coffee</p>
            </div>

            <div class="signup-form-wrapper">
                <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <div class="success-icon">✓</div>
                    <h3>Επιτυχής Εγγραφή!</h3>
                    <p><?php echo $success_message; ?></p>
                    <a href="<?php echo home_url(); ?>" class="btn-home">Επιστροφή στην αρχική</a>
                </div>
                <?php elseif (is_user_logged_in()): ?>
                <div class="logged-in-message">
                    <div class="info-icon">ℹ</div>
                    <h3>Έχετε ήδη λογαριασμό</h3>
                    <p>Είστε ήδη συνδεδεμένος ως <?php echo wp_get_current_user()->display_name; ?></p>
                    <a href="<?php echo home_url(); ?>" class="btn-home">Επιστροφή στην αρχική</a>
                </div>
                <?php else: ?>

                <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <div class="error-icon">⚠</div>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="post" id="signup-form">
                    <?php wp_nonce_field('register_nonce', 'nonce'); ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Όνομα:</label>
                            <input type="text" id="first_name" name="first_name"
                                value="<?php echo isset($_POST['first_name']) ? esc_attr($_POST['first_name']) : ''; ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Επώνυμο:</label>
                            <input type="text" id="last_name" name="last_name"
                                value="<?php echo isset($_POST['last_name']) ? esc_attr($_POST['last_name']) : ''; ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Όνομα Χρήστη:</label>
                        <input type="text" id="username" name="username"
                            value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>"
                            required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Κωδικός:</label>
                            <input type="password" id="password" name="password" required>
                            <small class="form-help">Τουλάχιστον 6 χαρακτήρες</small>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Επαλήθευση Κωδικού:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="place">Τόπος:</label>
                            <input type="text" id="place" name="place"
                                value="<?php echo isset($_POST['place']) ? esc_attr($_POST['place']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="zip">Τ.Κ.:</label>
                            <input type="text" id="zip" name="zip"
                                value="<?php echo isset($_POST['zip']) ? esc_attr($_POST['zip']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Διεύθυνση Παραλαβής:</label>
                        <input type="text" id="address" name="address"
                            value="<?php echo isset($_POST['address']) ? esc_attr($_POST['address']) : ''; ?>" required>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            Συμφωνώ με τους <a href="#" class="terms-link">Όρους και Προϋποθέσεις</a>
                        </label>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter">
                            <span class="checkmark"></span>
                            Θέλω να λαμβάνω newsletter με προσφορές και νέα
                        </label>
                    </div>

                    <button type="submit" name="signup_submit" class="signup-button" value="1">
                        Εγγραφή
                    </button>
                </form>

                <div class="signup-links">
                    <p class="have-account">Έχετε ήδη λογαριασμό;
                        <a href="<?php echo wp_login_url(); ?>" class="login-link">Συνδεθείτε εδώ</a>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
</main>

<script>
// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;

    if (password.length < 6) {
        this.style.borderColor = '#dc3545';
    } else if (password.length < 8) {
        this.style.borderColor = '#ffc107';
    } else {
        this.style.borderColor = '#28a745';
    }
});

// Confirm password validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (confirmPassword === password && confirmPassword.length > 0) {
        this.style.borderColor = '#28a745';
    } else if (confirmPassword.length > 0) {
        this.style.borderColor = '#dc3545';
    }
});
</script>

<?php get_footer(); ?>