<?php
get_header();

$current_post_id = get_the_ID(); // Get the current post's ID

$current_post = get_post($current_post_id); // Get the current post object
$taxonomies = wp_get_post_terms($current_post_id, 'departments', array('fields' => 'names'));


$page = $query = new WP_Query(
    array(
        'post_type' => 'page',
        'title'     => 'Job Apply Form',
    )
);
if ($current_post) {
//	dd(get_post_meta(get_the_ID()));
    ?>

   <div class="container">
       <table>
           <tr>
               <th>Job Title</th>
               <td><?php echo $current_post->post_title; ?></td>
           </tr>
           <tr>
               <th>Job Description</th>
               <td><?php echo $current_post->post_content; ?></td>
           </tr>
               <th>Start Date</th>
           <td><?php echo get_post_meta($current_post_id, 'start_date', true); ?></td>
           <tr>
               <th>End Date</th>
               <td><?php echo get_post_meta($current_post_id, 'end_date', true); ?></td>
           </tr>
           <tr>
               <th>Department</th>
               <td><?php echo implode(', ', $taxonomies) ?></td>
           </tr>
               <th>Apply</th>
           <td><a href="<?php echo get_permalink($query->post->ID); ?>?current_post_id=<?php echo $current_post_id; ?>">Apply</a></td>
           </tr>
           <tr>
       </table>
   </div>
    <?php
}
get_footer();
?>

