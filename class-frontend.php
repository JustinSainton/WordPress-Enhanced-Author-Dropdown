<?php
class WPEAD_Frontend {
    function __construct() {
		add_action( 'wp_dropdown_users', array( &$this, 'alter_dropdown_roles' ), 0, 1 );
    }

	public function alter_dropdown_roles( $output ) {
		global $post;

		if ( empty($post) ) return false;

		// strip out all whitespace
		$post_type_for_options = preg_replace('/\s*/', '', $post->post_type);
		// convert the string to all lowercase
		$post_type_for_options = strtolower($post_type_for_options);	
		// Return if we aren't using WPEAD on this post type :(
		$wpead_options = get_option( 'wpead_options' );
		$use_wpead_for_post_type = 	$wpead_options['wpead_use_for_'.$post_type_for_options];
		if ( !$use_wpead_for_post_type ) return $output;

		$this->add_select2();
		$args = array(
			'selected' => $post->post_author,
			'id' => 'post_author_override',
		);

		$output = $this->vendor_selectbox($args,$post_type_for_options,$wpead_options);

		return $output;
	}

	public function vendor_selectbox($args,$post_type_for_options,$wpead_options) {
		$default_args = array(
			'placeholder',
			'id',
			'class',
		);

		foreach ( $default_args as $key ) {
			if ( !is_array( $key ) && empty( $args[$key] ) ) $args[$key] = '';
			else if ( is_array( $key ) ) foreach ( $key as $val ) $args[$key][$val] = esc_attr( $args[$key][$val] );
		}
		extract($args);
		
		$roles = array();
		global $wp_roles, $post;
		
		foreach($wp_roles->role_names as $role => $role_name) {
		// strip out all whitespace
		$role_name_for_options = preg_replace('/\s*/', '', $role_name);
		// convert the string to all lowercase
		$role_name_for_options = strtolower($role_name_for_options);
		
		$use_wpead_for_role = 	$wpead_options['wpead_'.$post_type_for_options.'_for_'.$role_name_for_options];
		if ($use_wpead_for_role){
			array_push($roles,$role);
		}
		}
		
		$user_args = array( 'fields'  => array( 'ID', 'user_login' ) );
		// TODO include current user not admin by default

		$output = "<select style='width:200px;' name='$id' id='$id' class='$class' data-placeholder='$placeholder'>\n";
		$output .= "\t<option value=''></option>\n";
		
		// We need to make sure we include the post author, even their role is excluded from the dropdown
		$author = new WP_User($post->post_author);
		$author_role = $author->roles[0];
		// strip out all whitespace
		$author_role = preg_replace('/\s*/', '', $author_role);
		// convert the string to all lowercase
		$author_role = strtolower($author_role);
		$isexcluded = $wpead_options['wpead_'.$post_type_for_options.'_for_'.$author_role];
		if (!$isexcluded){
			// OH NO! The user's role is excluded. Let's include them manually!
			$select = selected($author->ID, $selected, false);
			$output .= "\t<option value='$author->ID' $select>$author->user_login</option>\n";		
		}
		
		foreach ( $roles as $role ) {

			$new_args = $user_args;
			$new_args['role'] = $role;
			$users = get_users( $new_args );
			if ( empty( $users ) ) continue;
			foreach ( (array) $users as $user ) {
				$select = selected($user->ID, $selected, false);
				$output .= "\t<option value='$user->ID' $select>$user->user_login</option>\n";
			}

		}
		$output .= "</select>";

		$output .= '<script type="text/javascript">jQuery(function() {jQuery("#'.$id.'").select2();});</script>';

		return $output;
	}

		public function add_select2()
		{
			wp_register_script( 'bootstrap-tooltip' , wpead_assets_url . '/settings/assets/js/bootstrap-tooltip.js' ,  array( 'jquery' ), '1.0' );
			wp_register_script( 'select2' , wpead_assets_url . '/settings/assets/js/select2/select2.min.js' ,  array( 'jquery' ), '1.0' );
			wp_register_script( 'sf-scripts' , wpead_assets_url . '/settings/assets/js/sf-jquery.js' ,  array( 'jquery' ), '1.0' );
			wp_register_style( 'select2' , wpead_assets_url . '/settings/assets/js/select2/select2.css' );
			wp_register_style( 'sf-styles' , wpead_assets_url . '/settings/assets/css/sf-styles.css' );

			wp_enqueue_script( 'bootstrap-tooltip' );
			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'sf-scripts' );
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'sf-styles' );
			wp_enqueue_style( 'farbtastic' );
		}
}