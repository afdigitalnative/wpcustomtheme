<?php

// Load Scripts
function load_devotion_scripts() {
	if( get_post_type() == 'devotion' )
	wp_register_script( 'upload', THEME_DIR . '/js/upload.js');
	wp_enqueue_script( 'upload' );
}
add_action( 'admin_enqueue_scripts', 'load_devotion_scripts' );

// Devotions
$devotions = register_cuztom_post_type( 'Devotion', array('has_archive' => 'devotions', 'menu_position' => 6, 'supports' => array('title', 'editor', 'author', 'thumbnail'), 'taxonomies' => array('category', 'post_tag'), 'rewrite' => array('slug' => 'devotion','with_front' => false)) );
//$devotions->add_taxonomy( 'Position' );

// Change 'Enter Title Here' Text
add_filter( 'enter_title_here', 'devotion_change_title' );
function devotion_change_title( $title ){
  global $post;
  if( 'devotion' == $post->post_type ):
    $title = 'Enter Devotion Name';
  endif;
  return $title;
}

// Add the Meta Box
function add_devotion_meta_box() {
	add_meta_box(
		'devotion_options', 		// $id
		'Author', 					// $title
		'show_devotion_options',  	// $callback
		'devotion', 				// $page
		'side', 					// $context
		'high' 						// $priority
	);
}
//add_action('add_meta_boxes', 'add_devotion_meta_box');

// Field Array
$prefix = 'devotion_';
$devotion_meta_fields = array(
);

// The Callback
function show_devotion_options() {

global $devotion_meta_fields, $post;

// Use nonce for verification
echo '<input type="hidden" name="metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($devotion_meta_fields as $field) {

		// get value of this field if it exists for this devotion
		$meta = get_post_meta($post->ID, $field['id'], true);

		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
				
				//Cases Go Here	
					
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="8" placeholder="'.$field['holder'].'"/>
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

				
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_devotion_meta($post_id) {
    global $devotion_meta_fields;
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
	foreach ($devotion_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'save_devotion_meta');

?>