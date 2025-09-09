<?php

/**
 * Handles user registration form submission.
 * This is the primary, correct function for handling signups.
 */
function kafekopteio_handle_signup_submission() {
    if (isset($_POST['signup_submit']) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'register_nonce')) {
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
        
        if (empty($username)) $errors[] = 'Το όνομα χρήστη είναι υποχρεωτικό.';
        if (username_exists($username)) $errors[] = 'Το όνομα χρήστη υπάρχει ήδη.';
        if (!is_email($email)) $errors[] = 'Παρακαλώ εισάγετε έγκυρο email.';
        if (email_exists($email)) $errors[] = 'Το email υπάρχει ήδη.';
        if (strlen($password) < 6) $errors[] = 'Ο κωδικός πρέπει να έχει τουλάχιστον 6 χαρακτήρες.';
        if ($password !== $confirm_password) $errors[] = 'Οι κωδικοί δεν ταιριάζουν.';
        if (empty($first_name)) $errors[] = 'Το όνομα είναι υποχρεωτικό.';
        if (empty($last_name)) $errors[] = 'Το επώνυμο είναι υποχρεωτικό.';
        
        if (!empty($errors)) {
            set_transient('signup_errors', $errors, 60);
            set_transient('signup_fields', $_POST, 60);
            wp_safe_redirect(get_permalink());
            exit;
        }
        
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass'  => $password,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'display_name' => $first_name . ' ' . $last_name,
        );
        $user_id = wp_insert_user($userdata);
        
        if (is_wp_error($user_id)) {
            set_transient('signup_errors', $user_id->get_error_messages(), 60);
            set_transient('signup_fields', $_POST, 60);
            wp_safe_redirect(get_permalink());
            exit;
        }
        
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_safe_redirect(add_query_arg('registered', 'true', get_permalink()));
        exit;
    }
}
add_action('init', 'kafekopteio_handle_signup_submission');

/**
 * Enqueues all theme styles and scripts.
 * This is the single, correct function for enqueuing.
 */
function kafekopteio_enqueue_scripts_and_styles() {
    // Enqueue Stylesheet
    wp_enqueue_style('kafekopteio-style', get_template_directory_uri() . '/style.css');

    // Enqueue Scripts
    wp_enqueue_script(
        'terra-basket',
        get_template_directory_uri() . '/js/basket.js',
        array(),
        '1.0.0',
        true
    );

    wp_enqueue_script(
        'kafekopteio-validation',
        get_template_directory_uri() . '/js/form-validation.js',
        array(),
        '1.0.0',
        true
    );

    // Localize script for AJAX
    wp_localize_script('terra-basket', 'terraAjax', array(
        'ajaxurl'    => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('submit_order_nonce'),
        'isLoggedIn' => is_user_logged_in()
    ));
}
add_action('wp_enqueue_scripts', 'kafekopteio_enqueue_scripts_and_styles');

/**
 * Handles AJAX order submission for both guests and logged-in users.
 */
function handle_submit_order() {
    global $wpdb;
    
    check_ajax_referer('submit_order_nonce', '_ajax_nonce');
    
    if (!isset($_POST['order_data'])) {
        wp_send_json_error('No order data received');
        return;
    }

    $order_data = json_decode(stripslashes($_POST['order_data']), true);
    $shipping_info = isset($_POST['shipping_info']) ? json_decode(stripslashes($_POST['shipping_info']), true) : [];
    
    $total = 0;
    foreach ($order_data as $item) {
        $total += floatval($item['price']) * intval($item['quantity']);
    }

    $wpdb->query('START TRANSACTION');

    try {
        $wpdb->insert(
            $wpdb->prefix . 'orders',
            array(
                'user_id' => get_current_user_id() ?: null,
                'total_amount' => $total,
                'shipping_info' => json_encode($shipping_info, JSON_UNESCAPED_UNICODE)
            ),
            array('%d', '%f', '%s')
        );
        
        $order_id = $wpdb->insert_id;

        foreach ($order_data as $item) {
            $wpdb->insert(
                $wpdb->prefix . 'order_items',
                array(
                    'order_id' => $order_id,
                    'product_name' => $item['name'],
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity']
                ),
                array('%d', '%s', '%f', '%d')
            );
        }

        if (!empty($shipping_info) && !get_current_user_id()) {
            $wpdb->insert(
                $wpdb->prefix . 'guest_orders',
                array(
                    'order_id' => $order_id,
                    'name' => sanitize_text_field($shipping_info['name']),
                    'email' => sanitize_email($shipping_info['email']),
                    'phone' => sanitize_text_field($shipping_info['phone']),
                    'address' => sanitize_text_field($shipping_info['address']),
                    'place' => sanitize_text_field($shipping_info['place']),
                    'zip' => sanitize_text_field($shipping_info['zip'])
                ),
                array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
            );
        }

        $wpdb->query('COMMIT');
        wp_send_json_success(array(
            'message' => 'Order saved successfully',
            'order_id' => $order_id
        ));

    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        error_log('Order insert failed: ' . $e->getMessage());
        wp_send_json_error($e->getMessage());
    }
}
add_action('wp_ajax_submit_order', 'handle_submit_order');
add_action('wp_ajax_nopriv_submit_order', 'handle_submit_order');

/**
 * Creates all custom database tables on theme activation.
 */
function create_custom_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Orders Table
    $orders_table = $wpdb->prefix . 'orders';
    $sql_orders = "CREATE TABLE IF NOT EXISTS $orders_table (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2),
        shipping_info TEXT,
        FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
    ) $charset_collate;";
    dbDelta($sql_orders);

    // Order Items Table
    $order_items_table = $wpdb->prefix . 'order_items';
    $sql_items = "CREATE TABLE IF NOT EXISTS $order_items_table (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_name VARCHAR(255),
        product_price DECIMAL(10,2),
        quantity INT,
        FOREIGN KEY (order_id) REFERENCES {$orders_table}(order_id)
    ) $charset_collate;";
    dbDelta($sql_items);

    // Guest Orders Table
    $guest_orders_table = $wpdb->prefix . 'guest_orders';
    $sql_guest = "CREATE TABLE IF NOT EXISTS $guest_orders_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_id INT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        place VARCHAR(100) NOT NULL,
        zip VARCHAR(20) NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY order_id (order_id)
    ) $charset_collate;";
    dbDelta($sql_guest);

    // Contact Form Submissions Table
    $contact_table_name = $wpdb->prefix . 'contact_form_submissions';
    $sql_contact = "CREATE TABLE $contact_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(100) NOT NULL,
        lname varchar(100) NOT NULL,
        phone varchar(20) DEFAULT '' NOT NULL,
        email varchar(100) NOT NULL,
        message text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta($sql_contact);
}
add_action('init', 'create_custom_tables');

/**
 * Handles the contact form submission and saves it to the database.
 */
function handle_contact_form_submission() {
    if ( isset($_POST['contact_form_submit']) && isset($_POST['contact_nonce']) && wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce') ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_form_submissions';

        $name    = sanitize_text_field($_POST['name']);
        $lname   = sanitize_text_field($_POST['lname']);
        $phone   = sanitize_text_field($_POST['phone']);
        $email   = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        $wpdb->insert(
            $table_name,
            array(
                'submission_date' => current_time('mysql'),
                'name'            => $name,
                'lname'           => $lname,
                'phone'           => $phone,
                'email'           => $email,
                'message'         => $message,
            )
        );

        $redirect_url = add_query_arg('submitted', 'true', get_permalink());
        wp_safe_redirect($redirect_url);
        exit;
    }
}
add_action('init', 'handle_contact_form_submission');

// Other functions can go here...