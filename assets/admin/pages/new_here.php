<?php

// Load jQuery UI
function load_newhere_style() {
	wp_enqueue_script('jquery-ui-datepicker');
}
add_action( 'admin_enqueue_scripts', 'load_newhere_style' );

// Add the Meta Box
function add_newhere_meta_box() {
	add_meta_box(
		'new_here_options', 		// $id
		'New Here Options', 		// $title
		'show_new_here_options', // $callback
		'page', 				// $page
		'normal', 				// $context
		'high' 					// $priority
	);
}
add_action('add_meta_boxes', 'add_newhere_meta_box');

// Field Array
$prefix = 'newhere_';
$newhere_meta_fields = array(
	array(
		'label' => 'Beliefs',
		'desc'	=> '',
		'id'	=> $prefix.'beliefs',
		'type'	=> 'editor'
	),
	array(
		'label' => 'FAQ',
		'desc'	=> '',
		'id'	=> $prefix.'faq',
		'type'	=> 'editor'
	)
);

// The Callback
function show_new_here_options() {

global $newhere_meta_fields, $post;

// Use nonce for verification
wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($newhere_meta_fields as $field) {

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
						echo "<input type='text' name='".$field['id']."' id='".$field['id']."' value='".$meta."' size='47' />
							<br /><span class='description'>".$field['desc']."</span>";
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
					
					// color palette
					case 'color':
					
					$getPalette = array();
					$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
					
					if($url != "") {
						$palette = colorPalette($url, 6);
						foreach($palette as $color) {
						    $getPalette[] = array(
						        'label' => $color,
						        'value' => $color,
						    );
						}
						foreach ( $getPalette as $option ) {
							echo '<div style="margin:8px;float:left"><input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'"><span style="background:#'.$option['label'].';display:inline-block;width:25px;height:25px;vertical-align:middle;"></span></label></div>';
						}
					} else {
						echo "Please upload a featured image and update this post to generate a color palette.";
					}
					
					break;
					
					// date
					case 'date':
						echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="47" /><br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// editor
					case 'editor':
					
						wp_editor( $meta, $field['id'], array('textarea_name' => $field['id']) );
					
					break;
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save The Data
function newhere_meta_save( $post_id ) {
    global $newhere_meta_fields;
    
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
    	
    // loop through fields and save the data
	foreach ($newhere_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action( 'save_post', 'newhere_meta_save' );

// Add Datepicker script
add_action('admin_head','add_newhere_scripts');
function add_newhere_scripts() {
	global $newhere_meta_fields, $post;
	
	$output = '<script type="text/javascript">
				jQuery(function() {';			
	foreach ($newhere_meta_fields as $field) {
		if($field['type'] == 'date')
		$output .= 'jQuery(".datepicker").datepicker({ minDate: 0, dateFormat: "MM d, yy" });';
	}
	$output .= '});
		</script>';
		
	echo $output;
}

?>