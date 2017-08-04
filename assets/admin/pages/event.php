<?php

// Load jQuery UI
function load_custom_wp_admin_style() {
	wp_enqueue_script('jquery-ui-datepicker');
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

// Add the Meta Box
function add_event_meta_box() {
	add_meta_box(
		'event_options', 		// $id
		'Event Options', 		// $title
		'show_event_options',  	// $callback
		'page', 				// $page
		'normal', 				// $context
		'high' 					// $priority
	);
}
add_action('add_meta_boxes', 'add_event_meta_box');

// Field Array
$prefix = 'event_';
$event_meta_fields = array(
	array(
		'label' => 'Accent Color',
		'id'	=> $prefix.'color',
		'type'	=> 'color'
	),
	array(
		'label'	=> 'Countdown Date',
		'desc'	=> '',
		'id'	=> $prefix.'date',
		'type'	=> 'date'
	),
	array(
		'label' => 'Form Type',
		'desc'	=> '(paste shortcode or URL for iframe)',
		'id'	=> $prefix.'form',
		'type'	=> 'text'
	),
	array(
		'label' => 'Contact Email(s)',
		'desc'	=> '(Seperate Multiple Emails With A Comma)',
		'id'	=> $prefix.'email',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Sun',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_0',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Mon',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_1',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Tues',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_2',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Wed',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_3',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Thurs',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_4',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Fri',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_5',
		'type'	=> 'text'
	),
	array(
		'label' => 'Event Times Sat',
		'desc'	=> '(Seperate Multiple Times With A Comma)',
		'id'	=> $prefix.'times_6',
		'type'	=> 'text'
	)
);

// The Callback
function show_event_options() {

global $event_meta_fields, $post;

// Use nonce for verification
wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($event_meta_fields as $field) {

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
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save The Data
function event_meta_save( $post_id ) {
    global $event_meta_fields;
    
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
    	
    // loop through fields and save the data
	foreach ($event_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action( 'save_post', 'event_meta_save' );

// Add Datepicker script
add_action('admin_head','add_custom_scripts');
function add_custom_scripts() {
	global $event_meta_fields, $post;
	
	$output = '<script type="text/javascript">
				jQuery(function() {';			
	foreach ($event_meta_fields as $field) {
		if($field['type'] == 'date')
		$output .= 'jQuery(".datepicker").datepicker({ minDate: 0, dateFormat: "MM d, yy" });jQuery(".datepicker_post").datepicker({ minDate: 0, dateFormat: "DD, MM d" });';
	}
	$output .= '});
		</script>';
		
	echo $output;
}

?>