<?php
add_action('rest_api_init', function () {
    register_rest_route('hiring/v1', '/apply/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'my_awesome_func',
        'permission_callback' => function () {
            return true;
        },
        'args' => array(
            'id' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
});

function my_awesome_func(WP_REST_Request $request)
{
    $id = $request->get_param('id');
    $user_email = $request->get_param('email');
    $user_name = sanitize_text_field($request->get_param('name'));
    $user_phone = $request->get_param('phone');

    $posts = get_posts(array(
        'id' => $id,
    ));

    if (empty($posts)) {
        return new WP_Error('no_job', 'Invalid author', array( 'status' => 404 ));
    }

    // Validate the data
    if (empty($user_email) || !is_email($user_email)) {
        return new WP_Error('invalid_email', 'Please provide a valid email address.', array('status' => 422));
    }

    if (empty($user_phone) || !preg_match('/^\d{10}$/', $user_phone)) {
        return new WP_Error('invalid_phone', 'Please provide a valid 10-digit phone number.', array('status' => 422));
    }
    global $wpdb;
    $job_applicants_table_name = $wpdb->prefix . 'job_applicants';
    $wpdb->insert(
        $job_applicants_table_name,
        [
            'name' => $user_name,
            'email' => $user_email,
            'phone' => $user_phone,
            'job_id' => $id,
        ],
        ['%s']
    );

    // Send a confirmation email to the user or do any other necessary tasks.

    // Return a response to the user.
    $response = array(
        'message' => 'Job application submitted successfully.',
    );

    return rest_ensure_response($response);
}
