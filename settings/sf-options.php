<?php
$options = array();
$post_types = get_post_types(); 

foreach ($post_types  as $post_type ) {
	
$post_type_display = str_replace('_', ' ', $post_type);
$post_type_display = ucwords($post_type_display);	

// It might be prudent to put a filter here for an array of exclusions. Maybe 1.1?
if (post_type_supports( $post_type, 'author' ) && $post_type != 'revision' && $post_type != 'nav_menu_item' && $post_type != 'attachment'){	
$options[] = array( 'name' => __( $post_type_display, 'wpead' ), 'type' => 'heading' );

	
// strip out all whitespace
$post_type_for_options = preg_replace('/\s*/', '', $post_type);
// convert the string to all lowercase
$post_type_for_options = strtolower($post_type_for_options);

$options[] = array(
	 'name' => __( 'Use for the '.$post_type_display.' Post Type', 'wpead' ),
	 'desc' => __( 'If checked, WPEAD will be used for this post type', 'wpead' ),
	 'id'   => 'wpead_use_for_'.$post_type_for_options,
	 'type' => 'checkbox',
	 'std'  => false,
);
$options[] = array( 'name' => __( 'Who to Include?', 'wpead' ), 'type' => 'title');
global $wp_roles;
foreach($wp_roles->role_names as $role => $role_name) {

// strip out all whitespace
$role_name_for_options = preg_replace('/\s*/', '', $role_name);
// convert the string to all lowercase
$role_name_for_options = strtolower($role_name_for_options);

$options[] = array(
	 'name' => __( 'Include '.$role_name.' in the dropdown?', 'wpead' ),
	 'desc' => __( 'If checked, will include users with of the '.$role_name.' role in the dropdown', 'wpead' ),
	 'id'   => 'wpead_'.$post_type_for_options.'_for_'.$role_name_for_options,
	 'type' => 'checkbox',
	 'std'  => false,
);

}
}
}