<?php

/**
 * Plugin Name: HiringPlugin
 * Plugin URI: PLUGIN SITE HERE
 * Description: PLUGIN DESCRIPTION HERE
 * Author: YOUR NAME HERE
 * Author URI: YOUR SITE HERE
 * Text Domain: HiringPlugin
 * Domain Path: /languages
 * Version: 0.1.0
 *
 * @package HiringPlugin
 */

function create_the_custom_table(): void
{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$jobs_table_name = $wpdb->prefix . 'posts';
	$job_applicants_table_name = $wpdb->prefix . 'job_applicants';

	$sql = "
        CREATE TABLE ".$job_applicants_table_name." (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            job_id BIGINT UNSIGNED NOT NULL,
            application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (job_id) REFERENCES $jobs_table_name(ID)
        );
    ";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

function my_plugin_remove_database()
{
	global $wpdb;
	$table = $wpdb->prefix . 'job_applicants';
	$sql = "DROP TABLE IF EXISTS $table";
	$wpdb->query($sql);
	delete_option('records_nums');
}

register_activation_hook(__FILE__, 'create_the_custom_table');
register_deactivation_hook(__FILE__, 'my_plugin_remove_database');

require __DIR__ . '/includes/frontend.php';
require __DIR__ . '/includes/backend.php';
require __DIR__ . '/includes/setting.php';
require __DIR__ . '/includes/page-template.php';

