<?php
add_filter('theme_page_templates', 'pt_add_page_template_to_dropdown');
add_filter('template_include', 'pt_change_page_template', 99);
add_action('wp_enqueue_scripts', 'pt_remove_style');
add_filter('template_include', 'custom_plugin_template_include');


/**
 * Add page templates.
 *
 * @param  array  $templates  The list of page templates
 *
 * @return array  $templates  The modified list of page templates
 */
function pt_add_page_template_to_dropdown($templates)
{
    $templates[plugin_dir_path(__FILE__) . 'templates/page-template.php'] = __('Page Template From Plugin', 'text-domain');
    $templates[plugin_dir_path(__FILE__) . 'templates/apply-form.php'] = __('apply form', 'text-domain');

    return $templates;
}

/**
 * Change the page template to the selected template on the dropdown
 *
 * @param $template
 *
 * @return mixed
 */
function pt_change_page_template($template)
{
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}

function pt_remove_style()
{
    // Change this "my-page" with your page slug
    if (is_page('my-page')) {
        $theme = wp_get_theme();

        $parent_style = $theme->stylesheet . '-style';

        wp_dequeue_style($parent_style);
        wp_deregister_style($parent_style);
        wp_deregister_style($parent_style . '-css');
    }
}
function list_pages()
{
           return new WP_Query(array('post_type'=>'job_vacancy',
					   'meta_key' => 'end_date',
					   'meta_compare' => '>',
					   'meta_value' =>current_time('mysql'),
					   'meta_type' =>'DATETIME',
			           'post_status'=>'publish',
					   'posts_per_page'=> get_option('records_nums'),
					   'order'=>'ASC',
					   'orderby'=>'title')
		   );
}

function custom_plugin_template_include($template)
{
    if (is_single() && get_post_type() == 'job_vacancy') {
        return plugin_dir_path(__FILE__) . 'templates/single-custom.php';
    }
    return $template;
}

function create_static_page_with_template_list_vacancies()
{
    // Check if the page already exists by title
    $page = $query = new WP_Query(
        array(
            'post_type'              => 'page',
            'title'                  => 'Job Vacancy List',
        )
    );

    if (empty($query->post)) {
        // Page doesn't exist, so let's create it
        $new_page = array(
            'post_title'    => 'Job Vacancy List',
            'post_content'  => '', // Add your content here
            'post_status'   => 'publish',
            'post_type'     => 'page',
        );

        // Insert the page into the database
        $page_id = wp_insert_post($new_page);
    } else {
        // Page with the title 'Custom Page' already exists
        $page_id = $query->post->ID;
    }

    // Assign the custom template to the page
    update_post_meta($page_id, '_wp_page_template', plugin_dir_path(__FILE__) . 'templates/page-template.php');
}
function create_static_page_with_template_apply(): void
{
    $page = $query = new WP_Query(
        array(
            'post_type'              => 'page',
            'title'                  => 'Job Apply Form',
        )
    );
    if (empty($query->post)) {
        // Page doesn't exist, so let's create it
        $new_page = array(
            'post_title'    => 'Job Apply Form',
            'post_content'  => '', // Add your content here
            'post_status'   => 'publish',
            'post_type'     => 'page',
        );

        // Insert the page into the database
        $page_id = wp_insert_post($new_page);
    } else {
        // Page with the title 'Custom Page' already exists
        $page_id = $query->post->ID;
    }

    // Assign the custom template to the page
    update_post_meta($page_id, '_wp_page_template', plugin_dir_path(__FILE__) . 'templates/apply-form.php');
}


add_action('init', 'create_static_page_with_template_list_vacancies');
add_action('init', 'create_static_page_with_template_apply');
