// add_action('wp_ajax_submit_order', 'kafekopteio_handle_submit_order');
// add_action('wp_ajax_nopriv_submit_order', 'kafekopteio_handle_submit_order');

// function kafekopteio_handle_submit_order() {
// global $wpdb;

// check_ajax_referer('submit_order_nonce', '_ajax_nonce');

// if (empty($_POST['order_data'])) {
// wp_send_json_error('No order data received');
// }

// $order_data = json_decode(stripslashes($_POST['order_data']), true);
// $shipping_info = isset($_POST['shipping_info']) ? json_decode(stripslashes($_POST['shipping_info']), true) : array();

// $table = $wpdb->prefix . 'product_orders';

// // Create table if it doesn't exist (safe fallback)
// if ( $wpdb->get_var( $wpdb->prepare("SHOW TABLES LIKE %s", $table) ) !== $table ) {
// $charset_collate = $wpdb->get_charset_collate();
// $sql = "CREATE TABLE {$table} (
// id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
// product_name VARCHAR(255) NOT NULL,
// product_price DECIMAL(10,2) NOT NULL,
// order_time DATETIME DEFAULT CURRENT_TIMESTAMP,
// order_hash VARCHAR(64) DEFAULT '',
// shipping_info LONGTEXT,
// user_id BIGINT(20) UNSIGNED DEFAULT 0,
// PRIMARY KEY (id),
// KEY order_hash (order_hash)
// ) {$charset_collate};";
// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
// dbDelta( $sql );
// }

// // Insert each item as a separate row and attach an order_hash
// $order_hash = wp_hash( time() . wp_rand() );
// foreach ( (array) $order_data as $item ) {
// $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
// $price = isset($item['price']) ? floatval($item['price']) : 0.0;

// $result = $wpdb->insert(
// $table,
// array(
// 'product_name' => $name,
// 'product_price' => $price,
// 'order_time' => current_time('mysql'),
// 'order_hash' => $order_hash,
// 'shipping_info' => maybe_serialize($shipping_info),
// 'user_id' => is_user_logged_in() ? get_current_user_id() : 0
// ),
// array('%s','%f','%s','%s','%s','%d')
// );

// if ($result === false) {
// wp_send_json_error('DB insert failed: ' . $wpdb->last_error);
// }
// }

// wp_send_json_success(array('order_hash' => $order_hash));
// }

// add_action('user_register','kafekopteio_save_extra_user_meta', 10, 1);
// function kafekopteio_save_extra_user_meta($user_id){
//     if (empty($user_id)) return;
//     if (!empty($_POST['first_name'])) update_user_meta($user_id,'first_name', sanitize_text_field($_POST['first_name']));
//     if (!empty($_POST['last_name']))  update_user_meta($user_id,'last_name',  sanitize_text_field($_POST['last_name']));
//     if (!empty($_POST['address']))    update_user_meta($user_id,'address',    wp_kses_post($_POST['address']));
//     if (!empty($_POST['place']))      update_user_meta($user_id,'place',      sanitize_text_field($_POST['place']));
//     if (!empty($_POST['zip']))        update_user_meta($user_id,'zip',        sanitize_text_field($_POST['zip']));
//     if (!empty($_POST['location']))   update_user_meta($user_id,'location',   sanitize_text_field($_POST['location']));
//     if (isset($_POST['newsletter']))  update_user_meta($user_id,'newsletter_subscribed', 1);
// }