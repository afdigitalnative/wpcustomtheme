<?php

// Load Scripts
function load_ministry_scripts() {
	if( get_post_type() == 'ministry' )
	wp_register_script( 'upload', THEME_DIR . '/js/upload.js');
	wp_enqueue_script( 'upload' );
}
add_action( 'admin_enqueue_scripts', 'load_ministry_scripts' );

// Ministries
$ministries = register_cuztom_post_type( 'Ministry', array('has_archive' => 'ministries', 'menu_position' => 6, 'supports' => array('title', 'editor', 'thumbnail', 'comments'), 'rewrite' => array('slug' => 'ministries','with_front' => false)) );

// Change 'Enter Title Here' Text
add_filter( 'enter_title_here', 'ministry_change_title' );
function ministry_change_title( $title ){
  global $post;
  if( 'ministry' == $post->post_type ):
    $title = 'Enter Ministry Name';
  endif;
  return $title;
}

// Add the Meta Box
function add_ministry_meta_box() {
	add_meta_box(
		'ministry_options', 		// $id
		'Ministry Options', 		// $title
		'show_ministry_options',  	// $callback
		'Ministry', 				// $page
		'normal', 					// $context
		'high' 						// $priority
	);
}
add_action('add_meta_boxes', 'add_ministry_meta_box');

// Get Users By Multiple Roles
function getUsersByRole($role_1, $role_2){
	// Set variable for array of users
	$allUsers_1 = array();
	$users_1 = get_users(array(
		'role' => $role_1
	));
	foreach ($users_1 as $user) {
	    $allUsers_1[] = array(
	        'label' => $user->display_name,
	        'value' => $user->ID,
	    );
	}
	
	// Set variable for array of users
	$allUsers_2 = array();
	$users_2 = get_users(array(
		'role' => $role_2
	));
	foreach ($users_2 as $user) {
	    $allUsers_2[] = array(
	        'label' => $user->display_name,
	        'value' => $user->ID,
	    );
	}
	
	// Merge Arrays
	$allUsers = array_merge($allUsers_1, $allUsers_2);
	
	// Add a blank value to the front of the array
	array_unshift(
		$allUsers,
		array (
			'label' => '',
			'value'	=> null
		)
	);
	
	return $allUsers;
}

// Get Users By Multiple Roles
function getUsersByRoles($role_1, $role_2, $role_3){
	// Set variable for array of users
	$allUsers_1 = array();
	$users_1 = get_users(array(
		'role' => $role_1
	));
	foreach ($users_1 as $user) {
	    $allUsers_1[] = array(
	        'label' => $user->display_name,
	        'value' => $user->ID,
	    );
	}
	
	// Set variable for array of users
	$allUsers_2 = array();
	$users_2 = get_users(array(
		'role' => $role_2
	));
	foreach ($users_2 as $user) {
	    $allUsers_2[] = array(
	        'label' => $user->display_name,
	        'value' => $user->ID,
	    );
	}
	
	// Set variable for array of users
	$allUsers_3 = array();
	$users_3 = get_users(array(
		'role' => $role_3
	));
	foreach ($users_3 as $user) {
	    $allUsers_3[] = array(
	        'label' => $user->display_name,
	        'value' => $user->ID,
	    );
	}
	
	// Merge Arrays
	$allUsers = array_merge($allUsers_1, $allUsers_2, $allUsers_3);
	
	// Add a blank value to the front of the array
	array_unshift(
		$allUsers,
		array (
			'label' => '',
			'value'	=> null
		)
	);
	
	return $allUsers;
}

// Field Array
$prefix = 'ministry_';
$ministry_meta_fields = array(
	array(
		'label'	=> '<h2>Sidebar</h2>',
		'id'	=> $prefix.'title_1',
		'type'	=> '',
	),
	array(
		'label' => 'Overseeing Pastor',
		'id'	=> $prefix.'pastor',
		'type'	=> 'select',
		'options' => getUsersByRoles('senior_pastor', 'executive_pastor', 'associate_pastor')
	),
	array(
		'label' => 'Ministry Leader',
		'id'	=> $prefix.'leader',
		'type'	=> 'select',
		'options' => getUsersByRole('ministry_leader', 'elder')
	),
	array(
		'label' => 'When',
		'desc'	=> 'When does this ministry meet?',
		'id'	=> $prefix.'when',
		'type'	=> 'text'
	),
	array(
		'label' => 'Where',
		'desc'	=> 'Where does this ministry meet?',
		'id'	=> $prefix.'where',
		'type'	=> 'text'
	),
	array(
		'label'	=> '<h2>Social Networks</h2>',
		'id'	=> $prefix.'title_2',
		'type'	=> '',
	),
	array(
		'label' => 'Facebook',
		'id'	=> $prefix.'facebook',
		'desc'  => 'Enter Facebook username.',
		'type'	=> 'text',
	),
	array(
		'label' => 'Twitter',
		'id'	=> $prefix.'twitter',
		'desc'  => 'Enter Twitter username.',
		'type'	=> 'text',
	),
	array(
		'label'	=> '<h2>Contact Form</h2>',
		'id'	=> $prefix.'title_3',
		'type'	=> '',
	),
	array(
		'label' => 'Email Address(es)',
		'desc'	=> '(Seperate Multiple Emails With A Comma)',
		'id'	=> $prefix.'email',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> '<h2>Calendar</h2>',
		'id'	=> $prefix.'title_4',
		'type'	=> '',
	),
	array(
		'label' => 'Calendar Database Index',
		'desc'	=> '',
		'id'	=> $prefix.'calendar',
		'type'	=> 'text'
	)
);

// The Callback
function show_ministry_options() {

global $ministry_meta_fields, $post;

// Use nonce for verification
echo '<input type="hidden" name="metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($ministry_meta_fields as $field) {

		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);

		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
				
				//Cases Go Here	
					
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="59" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// checkbox
					case 'checkbox':
						echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
							<label for="'.$field['id'].'">'.$field['desc'].'</label>';
					break;
					
					// select
					case 'select':
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						foreach ($field['options'] as $option) {
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// image
					case 'image':
						if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }
						echo	'<input name="'.$field['id'].'" type="text" class="custom_upload_image" value="'.$meta.'" size="59" />
								<input class="custom_upload_image_button button" type="button" value="'.$field['btn'].'" />
								<small> <a href="#" class="clear_field">Clear Field</a></small>
								<br clear="all" /><span class="description">'.$field['desc'].'';
					break;
					
					// author
					case 'author':

					    post_author_meta_box( $post );
					
					break;
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_ministry_meta($post_id) {
    global $ministry_meta_fields;
	// verify nonce
	if ( !isset($_POST['metabox_nonce']) || !wp_verify_nonce( $_POST['metabox_nonce'], basename(__FILE__)))
    	return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	// loop through fields and save the data
	foreach ($ministry_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'save_ministry_meta');

// Change Featured Image Location
add_action('do_meta_boxes', 'ministry_image_box');
function ministry_image_box() {
	remove_meta_box( 'postimagediv', 'customposttype', 'side' );
	add_meta_box('postimagediv', __('Main Banner'), 'post_thumbnail_meta_box', 'ministry', 'side');
}