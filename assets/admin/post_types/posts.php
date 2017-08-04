<?php

// Add the Meta Box
function add_post_meta_box() {
	add_meta_box(
		'post_options', 		// $id
		'Post Options', 		// $title
		'show_post_options',  	// $callback
		'post', 				// $page
		'normal', 				// $context
		'high' 					// $priority
	);
}
add_action('add_meta_boxes', 'add_post_meta_box');

// Field Array
$prefix = 'post_event_';
$post_meta_fields = array(
	array(
		'label'	=> '<h2>Event</h2>',
		'id'	=> $prefix.'title',
		'type'	=> '',
	),
	array(
		'label'	=> 'Date',
		'desc'	=> '',
		'id'	=> $prefix.'date',
		'type'	=> 'date'
	),
	array(
		'label' => 'Time',
		'desc'	=> '',
		'id'	=> $prefix.'time',
		'type'	=> 'text'
	),
	array(
		'label' => 'Cost',
		'desc'	=> '',
		'id'	=> $prefix.'cost',
		'type'	=> 'text'
	),
	array(
		'label' => 'Location',
		'desc'	=> '',
		'id'	=> $prefix.'location',
		'type'	=> 'text'
	)
);

// The Callback
function show_post_options() {

global $post_meta_fields, $post;

// Use nonce for verification
echo '<input type="hidden" name="metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($post_meta_fields as $field) {

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
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="15" />
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
					
					// date
					case 'date':
					
						echo '<input type="text" class="datepicker_post" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="15" /><br /><span class="description">'.$field['desc'].'</span>';
					break;
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_post_meta($post_id) {
    global $post_meta_fields;
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
	foreach ($post_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'save_post_meta');

?>