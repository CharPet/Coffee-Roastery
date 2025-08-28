<?php
// save-user-registration.php

// Load WordPress functions
require_once('../../../wp-load.php');

// Handle registration via WP APIs
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  wp_send_json_error('Method not allowed', 405);
}

if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'register_nonce') ) {
  wp_send_json_error('Invalid request', 400);
}

$username = sanitize_user($_POST['username'] ?? '');
$email = sanitize_email($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if ( empty($username) || empty($email) || empty($password) ) {
  wp_send_json_error('Missing required fields', 400);
}

if ($password !== $password_confirm) {
  wp_send_json_error('Passwords do not match', 400);
}

if ( username_exists($username) || email_exists($email) ) {
  wp_send_json_error('User already exists', 409);
}

// create WP user (password is hashed and stored in wp_users)
$user_id = wp_create_user($username, $password, $email);
if ( is_wp_error($user_id) ) {
  wp_send_json_error($user_id->get_error_message(), 500);
}

// optional: set display name / meta
update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name'] ?? ''));
update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name'] ?? ''));
update_user_meta($user_id, 'place', sanitize_text_field($_POST['place'] ?? ''));
update_user_meta($user_id, 'zip', sanitize_text_field($_POST['zip'] ?? ''));
update_user_meta($user_id, 'address', wp_kses_post($_POST['address'] ?? ''));
update_user_meta($user_id, 'newsletter_subscribed', isset($_POST['newsletter']) ? 1 : 0);

// if you still want the custom table, insert only non-sensitive extras
global $wpdb;
$table = $wpdb->prefix . 'user_registrations';
$wpdb->insert(
  $table,
  [
    'user_id' => $user_id,
    'username' => $username,
    'email' => $email,
    'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
    'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
    'place' => sanitize_text_field($_POST['place'] ?? ''),
    'zip' => sanitize_text_field($_POST['zip'] ?? ''),
    'address' => wp_kses_post($_POST['address'] ?? ''),
    'newsletter_subscribed' => isset($_POST['newsletter']) ? 1 : 0,
  ],
  ['%d','%s','%s','%s','%s','%s','%s','%s','%d']
);

wp_send_json_success(['user_id' => $user_id]);