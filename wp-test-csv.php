<?php
/**
 * Plugin Name: wp-test-csv
 * Description: Convert CSV data to WordPress post
 * Author:      Yevhenii Kirilov
 * Version:    0.2
 */

/**
 * Main file of plugin functions
 */
require_once plugin_dir_path(__FILE__) . 'includes/wp-test-csv-functions.php';

/**
 * Register styles and scripts of plugin
 */
add_action('admin_menu', 'wp_test_csv_enqueue_script');

function wp_test_csv_enqueue_script() {
    wp_enqueue_script( 'wp_test_csv_scripts', plugin_dir_url( __FILE__ ) . 'assets/js/wp_test_csv_scripts.js' );
    wp_enqueue_style( 'wp_test_csv_styles', plugin_dir_url( __FILE__ ) . 'assets/css/wp_test_csv_styles.css' );
}

register_activation_hook( __FILE__, 'wp_test_csv_activate' );

function wp_test_csv_activate() {
    WpTestCsv::activation();
}

register_uninstall_hook(__FILE__, 'wp_test_csv_uninstall');

function wp_test_csv_uninstall() {
    WpTestCsv::uninstall();
}
