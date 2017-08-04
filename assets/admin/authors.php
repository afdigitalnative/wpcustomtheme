<?php

// Change Author Base
function new_author_base() {
    global $wp_rewrite;
    $wp_rewrite->author_base = 'leaders';
    $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base. '/%author%';
}
add_action('init', 'new_author_base');

// Get User Role Name
function get_user_role($id) {
    $user = new WP_User($id);
    return array_shift($user->roles);
}

// Add New User Roles
function add_new_roles() {
	
	// New Roles To Be Added
	$new_roles = array(
		array(
			'role' 		=> 'senior_pastor',
			'display'	=> 'Senior Pastor'
		),
		array(
			'role' 		=> 'executive_pastor',
			'display'	=> 'Executive Pastor'
		),
		array(
			'role' 		=> 'associate_pastor',
			'display'	=> 'Associate Pastor'
		),
		array(
			'role' 		=> 'elder',
			'display'	=> 'Elder'
		),
		array(
			'role' 		=> 'ministry_leader',
			'display'	=> 'Ministry Leader'
		)
	);
	
	foreach($new_roles as $role){
		add_role($role['role'], $role['display'], array(
		    'edit_published_posts' => true,
		    'upload_files' => true,
		    'create_product' => true,
		    'publish_posts' => true,
		    'delete_published_posts' => true,
		    'edit_posts' => true,
		    'delete_posts' => true,
		    'read' => true
		));
	}
	
	// Old Roles To Be Removed
	$old_roles = array(
		'exec_pastor',
		'assoc_pastor'
		//'author'
		//'editor'
	);
	
	foreach($old_roles as $role){
		remove_role( $role );
	}
}
add_action('after_switch_theme', 'add_new_roles');

// Fix Post Author Dropdown
function author_override( $output ) {
	global $post, $user_ID;
	
	// return if this isn't the theme author override dropdown
	if (!preg_match('/post_author_override/', $output)) return $output;
	
	// return if we've already replaced the list (end recursion)
	if (preg_match ('/post_author_override_replaced/', $output)) return $output;
	
	// replacement call to wp_dropdown_users
	  $output = wp_dropdown_users(array(
	    'echo' => 0,
	  	'name' => 'post_author_override_replaced',
	  	'selected' => empty($post->ID) ? $user_ID : $post->post_author,
	  	'include_selected' => true
	  ));
	
	  // put the original name back
	  $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);
	
	return $output;
}
add_filter('wp_dropdown_users', 'author_override');

// Remove & Add Contact Options
function oe_contact_methods( $contactmethods ) { 
    // Remove Unwanted Methods
    unset( $contactmethods[ 'aim' ] );
    unset( $contactmethods[ 'yim' ] );
    unset( $contactmethods[ 'jabber' ] );
 
    // Add New Methods
    $contactmethods[ 'twitter' ] = 'Twitter Username';
    $contactmethods[ 'facebook' ] = 'Facebook Username';
 
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'oe_contact_methods' );

// Load Scripts
function load_author_scripts() {
	$hook = null;
	if( 'user-edit.php' != $hook )
	wp_enqueue_media();
	wp_enqueue_script( 'upload', THEME_DIR . '/js/upload-image.js' );
}
add_action( 'admin_enqueue_scripts', 'load_author_scripts' );

// Set Up Custom Options
$prefix = 'author_';
$author_meta_fields = array(
	array(
		'label' => 'Image',
		'id'	=> $prefix.'image',
		'type'	=> 'image',
		'desc'	=> 'Upload a main image for your profile page ( click above to edit image ).'
	),
	array(
		'label' => 'Description',
		'id'	=> $prefix.'about',
		'type'	=> 'editor',
		'desc'	=> 'Share a little biographical information to fill out your profile. This may be shown publicly.'
	),
	array(
		'label' => 'Birthday (year only)',
		'id'	=> $prefix.'bday',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Spouse',
		'id'	=> $prefix.'spouse',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Married Since',
		'id'	=> $prefix.'married',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Number of Children',
		'id'	=> $prefix.'children_num',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Saved Since',
		'id'	=> $prefix.'saved',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Previous Work Type',
		'id'	=> $prefix.'previous',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Hobbies',
		'id'	=> $prefix.'hobbies',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Testimony',
		'id'	=> $prefix.'testimony',
		'type'	=> 'text',
		'desc'	=> ''
	),
	array(
		'label' => 'Favorite Scripture',
		'id'	=> $prefix.'scripture',
		'type'	=> 'text',
		'desc'	=> ''
	)
);

// Add Custom Fields
function add_profile_fields( $user ) {
	
	global $author_meta_fields;
	
	?>
	<script type="text/javascript">
	(function($){ 
		// Remove the textarea before displaying visual editor
		$('#url, #description').parents('tr').remove();
	})(jQuery);
	</script>
	<?php
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	
	foreach ($author_meta_fields as $field) {
		
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
		
		$meta = get_the_author_meta($field['id'], $user->ID);
		
		switch($field['type']) {
		
			// text
			case 'text':
				
				echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" class="regular-text" value="'.$meta.'" />';
				echo '<p class="description">';
					_e($field['desc']);
				echo '</p>';
			break;
			
			// image
			case 'image':
			
				if($meta == ''){ $meta = get_template_directory_uri() . '/images/placeholder.jpg'; };
			
				echo '<img src="'.$meta.'" alt="upload_image" class="upload_image" style="width: 700px;" /><input name="'.$field['id'].'" id="'.$field['id'].'" class="upload_url" type="hidden" value="'.$meta.'" />';
				echo '<p class="description">';
					_e($field['desc']);
				echo '</p>';
			
			break;
			
			case 'editor':
			
				echo '<div style="width: 700px">';
					wp_editor( $meta, $field['id'] );
				echo '</div><p class="description">';
					_e($field['desc']);
				echo '</p>';
				
			break;
							
		} //end switch
		
		echo '</td></tr>';
		
	} // end foreach
	
	echo '</table>'; // end table
	
	?>
	<table class="form-table">
		<tr>
			<th><label>View</label></th>
			<td>	
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>">
					View This User
				</a>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'show_user_profile', 'add_profile_fields' );
add_action( 'edit_user_profile', 'add_profile_fields' );

function save_profile_fields( $user_id ) {	
	global $author_meta_fields;
	
	if ( !current_user_can( 'edit_user', $user_id ) ) return FALSE;
		
	foreach ($author_meta_fields as $field) {
		if($field['type'] == 'editor'){
			$content = $_POST[$field['id']];
			update_user_meta( $user_id, $field['id'], wpautop($content));
		} else {
			update_user_meta( $user_id, $field['id'], $_POST[$field['id']]);
		}	
	}

	update_user_meta( $user_id, 'user_image', $_POST['user_image'] );
}
add_action( 'personal_options_update', 'save_profile_fields' );
add_action( 'edit_user_profile_update', 'save_profile_fields' );