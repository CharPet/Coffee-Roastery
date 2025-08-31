<?php
// ...existing code...
function kafekopteio_enqueue_styles() {
    wp_enqueue_style('kafekopteio-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'kafekopteio_enqueue_styles');
// ...existing code...

// filepath: c:\Users\petir\Local Sites\kafekopteio4\app\public\wp-content\themes\kafekopteio\functions.php
function terra_enqueue_scripts() {
    wp_enqueue_script(
        'terra-basket', 
        get_template_directory_uri() . '/js/basket.js', 
        array('jquery'), 
        '1.0.0', 
        true
    );

    // Localize the script with new data
    wp_localize_script('terra-basket', 'terraAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('submit_order_nonce'),
        'isLoggedIn' => is_user_logged_in()
    ));
}
add_action('wp_enqueue_scripts', 'terra_enqueue_scripts');

function handle_submit_order() {
    global $wpdb;
    
    check_ajax_referer('submit_order_nonce', '_ajax_nonce');
    
    if (!isset($_POST['order_data'])) {
        wp_send_json_error('No order data received');
        return;
    }

    $order_data = json_decode(stripslashes($_POST['order_data']), true);
    $shipping_info = isset($_POST['shipping_info']) ? json_decode(stripslashes($_POST['shipping_info']), true) : [];
    
    // Calculate total
    $total = 0;
    foreach ($order_data as $item) {
        $total += floatval($item['price']) * intval($item['quantity']);
    }

    // Start transaction
    $wpdb->query('START TRANSACTION');

    try {
        // Insert main order
        $wpdb->insert(
            $wpdb->prefix . 'orders',
            array(
                'user_id' => get_current_user_id() ?: null,
                'total_amount' => $total,
                'shipping_info' => json_encode($shipping_info)
            ),
            array('%d', '%f', '%s')
        );
        
        $order_id = $wpdb->insert_id;

        // Insert order items
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

// Make sure the table exists
function create_orders_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Main orders table
    $orders_table = $wpdb->prefix . 'orders';
    $sql_orders = "CREATE TABLE IF NOT EXISTS $orders_table (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2),
        shipping_info TEXT,
        FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
    ) $charset_collate;";

    // Order items table (junction table)
    $order_items_table = $wpdb->prefix . 'order_items';
    $sql_items = "CREATE TABLE IF NOT EXISTS $order_items_table (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_name VARCHAR(255),
        product_price DECIMAL(10,2),
        quantity INT,
        FOREIGN KEY (order_id) REFERENCES {$orders_table}(order_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_orders);
    dbDelta($sql_items);
}
add_action('init', 'create_orders_tables');