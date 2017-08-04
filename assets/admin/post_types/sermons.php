<?php

// Load Scripts
function load_sermon_scripts() {
	if( get_post_type() == 'sermon' )
	wp_register_script( 'upload', THEME_DIR . '/js/upload.js');
	wp_enqueue_script( 'upload' );
}
add_action( 'admin_enqueue_scripts', 'load_sermon_scripts' );

// Sermon Taxonomy
register_cuztom_taxonomy( 'Series', 'sermon', array('hierarchical' => true, 'rewrite' => array('slug' => 'sermons','with_front' => false)));

// Sermon Post Type
register_cuztom_post_type( 'Sermon', array('has_archive' => 'sermons', 'menu_position' => 6, 'supports' => array('title', 'editor', 'author', 'thumbnail', 'comments'), 'rewrite' => array('slug' => 'sermons','with_front' => false)) );

// Rewrite the Sermon URL
function sermon_rewrite() {
	$cpt = 'sermon';
	$tax = 'series';
	
	add_rewrite_rule(
		'^'.$cpt.'s/([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type='.$cpt.'s&'.$tax.'=$matches[1]&paged=$matches[2]',
		'top'
	);
	add_rewrite_rule(
		'^'.$cpt.'s/([^/]+)/([^/]+)/?',
		'index.php?post_type='.$cpt.'s&'.$tax.'=$matches[1]&'.$cpt.'=$matches[2]',
		'top'
	);
	add_rewrite_rule(
		'^'.$cpt.'s/([^/]+)/([^/]+)/([^/]+)/?',
		'index.php?post_type='.$cpt.'s&'.$tax.'=$matches[1]&'.$tax.'=$matches[2]&'.$cpt.'=$matches[3]',
		'top'
	);
}
add_action('init','sermon_rewrite');

// Update Wordpress with the rewrite
function sermon_link( $link, $post = 0 ) {
	$cpt = 'sermon';
	$tax = 'series';
	$parent = null;
	
	$terms = get_the_terms($post->ID, $tax);
	
	if( !$terms ) {
		$child = 'other';
	} else {
		$child_obj = array_pop($terms);
		$parent_obj = get_term_by('id', $child_obj->parent, $tax);
		$child = $child_obj->slug;
		if ( $parent_obj ) {
			$parent = $parent_obj->slug;
		}
	}

    if ( $post->post_type == $cpt && $terms ) {
        return home_url(user_trailingslashit($cpt.'s/'.$parent.'/'.$child.'/'.$post->post_name));
    } else if ( $post->post_type == $cpt && !$terms ) {
    	return home_url(user_trailingslashit($cpt.'s/'.$child.'/'.$post->post_name));
    } else {
        return $link;
    }
}
add_filter('post_type_link', 'sermon_link', 1, 3);

// Change 'Enter Title Here' Text
function sermon_change_title( $title ){
  global $post;
  if( 'sermon' == $post->post_type ):
    $title = 'Enter Sermon Title';
  endif;
  return $title;
}
add_filter( 'enter_title_here', 'sermon_change_title' );

// Default To "Other" If No Taxonomy Is Selected
function default_taxonomy_term( $post_id, $post ) {
    if ( 'publish' === $post->post_status ) {
        $defaults = array(
            'series' => array( 'Other'),
            );
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}
add_action( 'save_post', 'default_taxonomy_term', 100, 2 );

// Add the Meta Box
function add_custom_meta_box() {
	add_meta_box(
		'sermon_options', 		// $id
		'Sermon Options', 		// $title
		'show_sermon_options',  // $callback
		'Sermon', 				// $page
		'normal', 				// $context
		'high' 					// $priority
	);
}
add_action('add_meta_boxes', 'add_custom_meta_box');

// Set variable for array of leaders
$getPastors = array();
$args = array(
	'post_type' => 'leader',
	'position' => 'pastor',
	'orderby' => 'title',
	'order' => 'ASC'
);
$pastors = new WP_Query($args);
$posts = $pastors->get_posts();
foreach ($posts as $post) {
    $getPastors[] = array(
        'label' => $post->post_title,
        'value' => $post->ID,
    );
}
// Add a blank value to the front of the array
array_unshift(
	$getPastors,
	array (
		'label' => '',
		'value'	=> null
	)
);

// Remove Author Meta Box
function remove_sermon_author() {
    remove_meta_box( 'authordiv', 'sermon', 'normal' );
}
add_action( 'admin_menu', 'remove_sermon_author' );

// Field Array
$prefix = 'sermon_';
$sermon_meta_fields = array(
	array(
		'label'	=> '<h2>Speaker</h2>',
		'id'	=> $prefix.'title_1',
		'type'	=> '',
	),
	array(
		'label' => 'Speaker',
		'id'	=> $prefix.'speaker',
		'type'	=> 'author'
	),
	array(
		'label' => 'Guest Speaker (if applicable)',
		'id'	=> $prefix.'guest',
		'type'	=> 'text',
		'desc'=> '',
		'holder'=> ''
	),
	array(
		'label'	=> '<h2>Scripture Reference</h2>',
		'id'	=> $prefix.'title_2',
		'type'	=> '',
	),
	array(
		'label'=> 'Passage',
		'id'	=> $prefix.'passage',
		'type'	=> 'select',
		'options' => array (
			'null' => array (
				'label' => '',
				'value'	=> null
			),
			
			// Old Testament
			'1' => array (
				'label' => 'Genesis',
				'value'	=> 'Genesis'
			),
			'2' => array (
				'label' => 'Exodus',
				'value'	=> 'Exodus'
			),
			'3' => array (
				'label' => 'Leviticus',
				'value'	=> 'Leviticus'
			),
			'4' => array (
				'label' => 'Numbers',
				'value'	=> 'Numbers'
			),
			'5' => array (
				'label' => 'Deuteronomy',
				'value'	=> 'Deuteronomy'
			),
			'6' => array (
				'label' => 'Joshua',
				'value'	=> 'Joshua'
			),
			'7' => array (
				'label' => 'Judges',
				'value'	=> 'Judges'
			),
			'8' => array (
				'label' => 'Ruth',
				'value'	=> 'Ruth'
			),
			'9' => array (
				'label' => '1 Samuel',
				'value'	=> '1 Samuel'
			),
			'10' => array (
				'label' => '2 Samuel',
				'value'	=> '2 Samuel'
			),
			'11' => array (
				'label' => '1 Kings',
				'value'	=> '1 Kings'
			),
			'12' => array (
				'label' => '2 Kings',
				'value'	=> '2 Kings'
			),
			'13' => array (
				'label' => '1 Chronicles',
				'value'	=> '1 Chronicles'
			),
			'14' => array (
				'label' => '2 Chronicles',
				'value'	=> '2 Chronicles'
			),
			'15' => array (
				'label' => 'Ezra',
				'value'	=> 'Ezra'
			),
			'16' => array (
				'label' => 'Nehemiah',
				'value'	=> 'Nehemiah'
			),
			'17' => array (
				'label' => 'Esther',
				'value'	=> 'Esther'
			),
			'18' => array (
				'label' => 'Job',
				'value'	=> 'Job'
			),
			'19' => array (
				'label' => 'Psalms',
				'value'	=> 'Psalms'
			),
			'20' => array (
				'label' => 'Proverbs',
				'value'	=> 'Proverbs'
			),
			'21' => array (
				'label' => 'Ecclesiastes',
				'value'	=> 'Ecclesiastes'
			),
			'22' => array (
				'label' => 'Song of Solomon',
				'value'	=> 'Song of Solomon'
			),
			'23' => array (
				'label' => 'Isaiah',
				'value'	=> 'Isaiah'
			),
			'24' => array (
				'label' => 'Jeremiah',
				'value'	=> 'Jeremiah'
			),
			'25' => array (
				'label' => 'Lamentations',
				'value'	=> 'Lamentations'
			),
			'26' => array (
				'label' => 'Ezekiel',
				'value'	=> 'Ezekiel'
			),
			'27' => array (
				'label' => 'Daniel',
				'value'	=> 'Daniel'
			),
			'28' => array (
				'label' => 'Hosea',
				'value'	=> 'Hosea'
			),
			'29' => array (
				'label' => 'Joel',
				'value'	=> 'Joel'
			),
			'30' => array (
				'label' => 'Amos',
				'value'	=> 'Amos'
			),
			'31' => array (
				'label' => 'Obadiah',
				'value'	=> 'Obadiah'
			),
			'32' => array (
				'label' => 'Jonah',
				'value'	=> 'Jonah'
			),
			'33' => array (
				'label' => 'Micah',
				'value'	=> 'Micah'
			),
			'34' => array (
				'label' => 'Nahum',
				'value'	=> 'Nahum'
			),
			'35' => array (
				'label' => 'Habakkuk',
				'value'	=> 'Habakkuk'
			),
			'36' => array (
				'label' => 'Zephaniah',
				'value'	=> 'Zephaniah'
			),
			'37' => array (
				'label' => 'Haggai',
				'value'	=> 'Haggai'
			),
			'38' => array (
				'label' => 'Zechariah',
				'value'	=> 'Zechariah'
			),
			'39' => array (
				'label' => 'Malachi',
				'value'	=> 'Malachi'
			),
			
			// New Testament
			'40' => array (
				'label' => 'Matthew',
				'value'	=> 'Matthew'
			),
			'41' => array (
				'label' => 'Mark',
				'value'	=> 'Mark'
			),
			'42' => array (
				'label' => 'Luke',
				'value'	=> 'Luke'
			),
			'43' => array (
				'label' => 'John',
				'value'	=> 'John'
			),
			'44' => array (
				'label' => 'Acts',
				'value'	=> 'Acts'
			),
			'45' => array (
				'label' => 'Romans',
				'value'	=> 'Romans'
			),
			'46' => array (
				'label' => '1 Corinthians',
				'value'	=> '1 Corinthians'
			),
			'47' => array (
				'label' => '2 Corinthians',
				'value'	=> '2 Corinthians'
			),
			'48' => array (
				'label' => 'Galatians',
				'value'	=> 'Galatians'
			),
			'49' => array (
				'label' => 'Ephesians',
				'value'	=> 'Ephesians'
			),
			'50' => array (
				'label' => 'Philippians',
				'value'	=> 'Philippians'
			),
			'51' => array (
				'label' => 'Colossians',
				'value'	=> 'Colossians'
			),
			'52' => array (
				'label' => '1 Thessalonians',
				'value'	=> '1 Thessalonians'
			),
			'53' => array (
				'label' => '2 Thessalonians',
				'value'	=> '2 Thessalonians'
			),
			'54' => array (
				'label' => '1 Timothy',
				'value'	=> '1 Timothy'
			),
			'55' => array (
				'label' => '2 Timothy',
				'value'	=> '2 Timothy'
			),
			'56' => array (
				'label' => 'Titus',
				'value'	=> 'Titus'
			),
			'57' => array (
				'label' => 'Philemon',
				'value'	=> 'Philemon'
			),
			'58' => array (
				'label' => 'Hebrews',
				'value'	=> 'Hebrews'
			),
			'59' => array (
				'label' => 'James',
				'value'	=> 'James'
			),
			'60' => array (
				'label' => '1 Peter',
				'value'	=> '1 Peter'
			),
			'61' => array (
				'label' => '2 Peter',
				'value'	=> '2 Peter'
			),
			'62' => array (
				'label' => '1 John',
				'value'	=> '1 John'
			),
			'63' => array (
				'label' => '2 John',
				'value'	=> '2 John'
			),
			'64' => array (
				'label' => '3 John',
				'value'	=> '3 John'
			),
			'65' => array (
				'label' => 'Jude',
				'value'	=> 'Jude'
			),
			'66' => array (
				'label' => 'Revelation',
				'value'	=> 'Revelation'
			),
		)
	),
	array(
		'label' => 'Start Verse',
		'id'	=> $prefix.'start_verse', // Make Unique On Mulitples
		'desc'  => '',
		'type'	=> 'text',
		'holder'=> 'e.g. 5:12'
	),
	array(
		'label' => 'End Verse',
		'id'	=> $prefix.'end_verse', // Make Unique On Mulitples
		'desc'  => '',
		'type'	=> 'text',
		'holder'=> 'e.g. 5:15'
	),
	array(
		'label' => 'Text',
		'desc'	=> 'Enter Scripture Referenced',
		'id'	=> $prefix.'text',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> '<h2>Pastor Notes</h2>',
		'id'	=> $prefix.'title_3',
		'type'	=> '',
	),
	array(
		'label' => 'Text',
		'desc'	=> 'Enter Any Available Notes',
		'id'	=> $prefix.'notes',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> '<h2>Audio Content</h2>',
		'id'	=> $prefix.'title_4',
		'type'	=> '',
	),
	array(
		'label' => 'Audio Source',
		'id'	=> $prefix.'audio',
		'desc'  => '',
		'type'	=> 'image',
		'btn'   => 'Choose Audio'
	),
	array(
		'label'	=> '<h2>Video Content</h2>',
		'id'	=> $prefix.'title_5',
		'type'	=> '',
	),
	array(
		'label' => 'Video Source',
		'id'	=> $prefix.'video',
		'desc'  => '',
		'type'	=> 'image',
		'btn'   => 'Choose Video'
	),
	array(
		'label'	=> '<h2>Documents</h2>',
		'id'	=> $prefix.'title_6',
		'type'	=> '',
	),
	array(
		'label' => 'Document Source',
		'id'	=> $prefix.'document',
		'desc'  => '',
		'type'	=> 'image',
		'btn'   => 'Choose File'
	)
);

// The Callback
function show_sermon_options() {

global $sermon_meta_fields, $post;

// Use nonce for verification
echo '<input type="hidden" name="metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';

	foreach ($sermon_meta_fields as $field) {

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
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="14" placeholder="'.$field['holder'].'"/>
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
function save_sermon_meta($post_id) {
    global $sermon_meta_fields;
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
	foreach ($sermon_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'save_sermon_meta');

// Change Featured Image Location
add_action('do_meta_boxes', 'sermon_image_box');
function sermon_image_box() {
	remove_meta_box( 'postimagediv', 'customposttype', 'side' );
	add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', 'sermon', 'side', 'high');
}


add_filter("manage_edit-sermon_columns", "business_manager_edit_columns");
    function business_manager_edit_columns($columns){
	$columns = array(
	"cb" => "<input type=\"checkbox\" />",
	"title" => "Title",
	"description" => "Description",
	"cat" => "Series",
	"featured" => "Image",
	);
	return $columns;
}

add_action("manage_sermon_posts_custom_column", "business_manager_custom_columns");
function business_manager_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	switch ($column) {
		case "description":
			the_excerpt();
		break;
		case "cat":
			echo get_the_term_list($post->ID, 'series');
		break;
		case "featured":
			echo the_post_thumbnail(array(250, 140));
		break;
	} 
}

?>