<?php
// ...existing code...
function kafekopteio_enqueue_styles() {
    wp_enqueue_style('kafekopteio-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'kafekopteio_enqueue_styles');
// ...existing code...

// filepath: c:\Users\petir\Local Sites\kafekopteio4\app\public\wp-content\themes\kafekopteio\functions.php
function terra_coffee_enqueue_scripts() {
    wp_enqueue_script(
        'basket-js',
        get_template_directory_uri() . '/js/basket.js',
        array(), // dependencies
        null,
        true // load in footer
    );

        wp_localize_script('basket-js', 'ajaxurl', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'terra_coffee_enqueue_scripts');

add_action('wp_ajax_submit_order', 'handle_order_submission');
add_action('wp_ajax_nopriv_submit_order', 'handle_order_submission');

function handle_order_submission() {
    global $wpdb;

    $order_data = json_decode(stripslashes($_POST['order_data']), true);

    if (!is_array($order_data)) {
        wp_send_json_error('Invalid order data');
        return;
    }

    foreach ($order_data as $item) {
        $wpdb->insert(
            $wpdb->prefix . 'product_orders',
            array(
                'product_name' => sanitize_text_field($item['name']),
                'product_price' => floatval($item['price']),
                'order_time'    => current_time('mysql', 1),
            )
        );
    }

    wp_send_json_success('Order saved');
}