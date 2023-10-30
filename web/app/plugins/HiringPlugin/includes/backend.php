<?php
function register_job_post_type()
{
    register_post_type('job_vacancy', array(
        'labels' => array(
            'name' => 'Job Vacancies',
            'singular_name' => 'Job Vacancy',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
    ));
}

add_action('init', 'create_deb_taxonomy', 0);

function create_deb_taxonomy()
{

    $labels = array(
        'name' => _x('Departments', 'taxonomy general name'),
        'singular_name' => _x('Department', 'taxonomy singular name'),
        'search_items' =>  __('Search Departments'),
        'popular_items' => __('Popular Departments'),
        'all_items' => __('All Departments'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Department'),
        'update_item' => __('Update Department'),
        'add_new_item' => __('Add New Department'),
        'new_item_name' => __('New Department Name'),
        'separate_items_with_commas' => __('Separate Departments with commas'),
        'add_or_remove_items' => __('Add or remove Departments'),
        'choose_from_most_used' => __('Choose from the most used Departments'),
        'menu_name' => __('Departments'),
    );


    register_taxonomy('departments', 'job_vacancy', array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array( 'slug' => 'departments' ),
    ));
}
add_action('init', 'register_job_post_type');

function save_custom_meta_fields($post_id) {
	create_field();
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;
    global $wpdb;
	$table_name = $wpdb->prefix . 'job_applicants';
	$sql = "SELECT email FROM $table_name";
	$results = $wpdb->get_results($sql);
	foreach ($results as $result) {
		$to = $result->email;
		$subject = 'Your Subject Here';
		$message = 'Hello, this is the message content.';
		$headers = 'From: Your Name <your_email@example.com>' . "\r\n";
         wp_mail($to, $subject, $message, $headers);
	}}

add_action('save_post', 'save_custom_meta_fields');

