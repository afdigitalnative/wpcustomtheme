<?php
// Display Sermon Media
function display_sermon_media(){
	global $post;
	$post_meta_data = get_post_custom('post_type=sermons');
	?>
	<?php // AUDIO ?>
	<?php if(isset($post_meta_data['sermon_audio'])) { ?>
		<div id="audio-wrap">
			<audio controls="" preload="none" width="640" height="30" src="<?php echo $post_meta_data['sermon_audio'][0]; ?>"></audio>
			<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>" class="poster"/>
		</div>
	<?php } ?>
	<?php // VIDEO ?>
	<?php if(isset($post_meta_data['sermon_video'])) { ?>
		<div id="video-wrap">
			<video controls="" width="640" height="360" poster="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>">
				<source src="<?php echo $post_meta_data['sermon_video'][0]; ?>" type="video/mp4" />
			</video>
		</div>
	<?php } ?>
	<?php
}

// Create Dynamic Tabs
function createTabs( $n ){
	$tabs = explode(',', $n);
	$count = count($tabs);
	$i = 0;
	
	foreach($tabs as $tab) {
		if ($count == 5) {
			$class = 'tab-5';
		} else if ($count == 4) {
			$class = 'tab-4';
		} else {
			$class = 'tab-3';
		}
		
		if ($i == 0) {
			$class .= ' current'; 
		}
		
		echo '<li><a href="#'.strtolower($tab).'" class="'.$class.'">'.$tab.'</a></li>';
		
		$i++;
	}
}

// Filter out password protected posts
// use add_filter( 'posts_where', 'post_password_filter' ) before AND remove_filter( 'posts_where', 'post_password_filter' ) after
function post_password_filter( $where = '' ) {
    $where .= " AND post_password = ''";
    return $where;
}

// Encrypt Email
function convertEmail($email) {
    $p = str_split(trim($email));
    $new_mail = '';
    foreach ($p as $val) {
        $new_mail .= '&#'.ord($val).';';
    }
    return $new_mail;
}

// Get Category ID
function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

// Create User Link
function userLink( $id ) {
	$user = get_user_by('id', $id);
	echo '<a href="'.get_author_posts_url($user->ID).'" title="'.$user->display_name.'">'.$user->display_name.'</a>';
}

// Create a URL Safe Title
function urlSafeTitle() {
	 global $post;
	$temptitle = strtolower(str_replace(' ', '-', the_title('', '', false)));
	$urlsafe = str_replace("%26%238217%3B", "", urlencode($temptitle));
	return $urlsafe;
}

// Add Read More Link To Excerpt
function excerpt_read_more($output) {
    global $post, $more_text;
    return $output . '<a href="'. get_permalink( $post->ID ) .'" class="readmore">'. $more_text .'</a>';
}
add_filter( 'the_excerpt', 'excerpt_read_more' );

// Change Excerpt Length
function custom_excerpt_length( $length ) {
	return 52;
}
add_filter( 'excerpt_length', 'custom_excerpt_length' );

// Sharrre
function sharrre() {
	?>
	<div id="share" class="group">
		<span class="sidebar-title">Share</span>
		<div id="facebook" data-url="<?php echo wp_get_shortlink(); ?>" data-text="<?php echo get_the_excerpt(); ?>"></div>
		<div id="twitter" data-url="<?php echo wp_get_shortlink(); ?>" data-text="<?php echo get_the_excerpt(); ?>"></div>
		<div id="share-link" class="sharrre">
			<span>o</span>
		</div>
		<input type='text' value='<?php echo wp_get_shortlink(); ?>' onclick='this.focus(); this.select();' id="short-link"/>
	</div>
	<?php 
}

// Count Post Views
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Limit Word Length
function limit_words($string, $word_limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}

// Create a color palette from a photo
function colorPalette($imageFile, $numColors= 6, $granularity = 5) { 
   $granularity = max(1, abs((int)$granularity)); 
   $colors = array(); 
   $size = @getimagesize($imageFile); 
   if($size === false) { 
      user_error("Unable to get image size data"); 
      return false; 
   }
   $imgData = file_get_contents($imageFile);
   $img = @imagecreatefromstring($imgData);
   unset($imgData);
   if(!$img) { 
      user_error("Unable to open image file"); 
      return false; 
   } 
   for($x = 0; $x < $size[0]; $x += $granularity) { 
      for($y = 0; $y < $size[1]; $y += $granularity) { 
         $thisColor = imagecolorat($img, $x, $y); 
         $rgb = imagecolorsforindex($img, $thisColor); 
         $red = round(round(($rgb['red'] / 0x33)) * 0x33);  
         $green = round(round(($rgb['green'] / 0x33)) * 0x33);  
         $blue = round(round(($rgb['blue'] / 0x33)) * 0x33);  
         $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue); 
         if(array_key_exists($thisRGB, $colors)) { 
            $colors[$thisRGB]++; 
         } else { 
            $colors[$thisRGB] = 1; 
         } 
      } 
   } 
   arsort($colors); 
   return array_slice(array_keys($colors), 0, $numColors); 
}

// Convert Heaxadecimal colors to RGB
function hex2rgb( $colour, $opacity = 1 ) {
	if ( $colour[0] == '#' ) {
		$colour = substr( $colour, 1 );
	}
	if ( strlen( $colour ) == 6 ) {
		list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
	} elseif ( strlen( $colour ) == 3 ) {
		list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
	} else {
		return false;
	}
	$r = hexdec( $r );
	$g = hexdec( $g );
	$b = hexdec( $b );
	$rgb = array( 'red' => $r, 'green' => $g, 'blue' => $b );
	echo 'rgba('.implode(', ', $rgb).', '.$opacity.')';
}

// Adjust brightness of hex color
function colorBrightness($hex, $percent) {
	// Work out if hash given
	$hash = '';
	if (stristr($hex,'#')) {
		$hex = str_replace('#','',$hex);
		$hash = '#';
	}
	/// HEX TO RGB
	$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	//// CALCULATE
	for ($i=0; $i<3; $i++) {
		// See if brighter or darker
		if ($percent > 0) {
			// Lighter
			$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
		} else {
			// Darker
			$positivePercent = $percent - ($percent*2);
			$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
		}
		// In case rounding up causes us to go to 256
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
	}
	//// RBG to Hex
	$hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
		$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	// Check if shorthand hex value given (eg. #FFF instead of #FFFFFF)
	if(strlen($hex) == 3) {
		$hex = str_repeat(substr($hex,0,1), 2) . str_repeat(substr($hex,1,1), 2) . str_repeat(substr($hex,2,1), 2);
	}
	return $hash.$hex;
}

// Related Posts
function related_posts($related_type, $related_tax, $limit, $link_name) {

	global $wpdb, $post;
	
	// Get The Related Term
	$terms = array();
	foreach(wp_get_object_terms($post->ID, $related_tax) as $term){
		$terms[] = $term->slug;
	};
	
	// Grab The First Term From The Array
	$related_term = array_shift(array_values($terms));
	
	// Store Current Post ID
	$current = $post->ID;
	
	// Query The Related Posts
	$related_posts = $wpdb->get_results( 
		"
	    SELECT * 
		FROM $wpdb->posts
		LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
		LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
		WHERE $wpdb->posts.post_type = '$related_type' 
		AND $wpdb->posts.post_status = 'publish'
		AND $wpdb->term_taxonomy.taxonomy = '$related_tax'
		AND $wpdb->terms.slug = '$related_term'
		ORDER BY post_date DESC
		LIMIT $limit
	    "
	);
	
	if($related_posts) {
		echo '<ul class="related-'.$related_type.'">';
			foreach ($related_posts as $post):setup_postdata($post);
			
			$post_meta_data = get_post_custom('post_type=sermons');
			
			if(isset($post_meta_data['sermon_passage'][0])){
				$verse = $post_meta_data['sermon_passage'][0].' '.$post_meta_data['sermon_start_verse'][0].' - '.$post_meta_data['sermon_end_verse'][0];
			} else {
				$verse = null;
			}
			
			if($current == $post->ID){
				echo '<li><a href="'.get_permalink().'" class="current"><span>'.get_the_title().'</span><span>'.$verse.'</span></a></li>';
			} else {
				echo '<li><a href="'.get_permalink().'"><span>'.get_the_title().'</span><span>'.$verse.'</span></a></li>';
			}
			endforeach;
			echo '<li><a href="'.get_term_link( $related_term, $related_tax ).'" class="all-'.$related_type.'">'.$link_name.'</a></li>';
		echo '</ul>';
	} else {
		echo 'No related '. Inflector::pluralize($related_type) .' found';
	}
	wp_reset_postdata();
}

// Database Shortcode
function events_db_shortcode($atts) {
	extract(shortcode_atts(array(
	    'ministry' => '1',
	    'limit' => '5'
	), $atts));
	
	global $wpdb;
	
	// $query = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	
	// Access external database
	$events_db = new wpdb('356279_dbadmin16', 'HoM3FR33!', '356279_revivalgc', 'mysql50-62.wc1');
	$events_db->show_errors();
	
	// Query Events Database (use variable for ministry to make it dynamic)
	$events = $events_db->get_results(
		"
		SELECT ID, EventName, CoordinatorName, EventDescription, Location
		FROM  gc_events
		WHERE PrivateEvent = 0
		AND Ministry = $ministry
		LIMIT $limit
		"
	);
	
	// Create the event data that will be displayed
	foreach ($events as $event) :
	    echo '<strong>Event Name:</strong> ' . $event->EventName . '<br /><strong>Leader:</strong> ' . $event->CoordinatorName . '<br /><strong>Description:</strong> ' . $event->EventDescription;
	    echo '<br /><strong>Location:</strong> ';
	    
	    // If location is not empty then it is offsite so show it
	    if ($event->Location != "") {
	    	echo $event->Location . '<br />';
	 	
	    // If it is empty lets setup an onsite location to show
		} else {
			// Take the event ID and compare it with the onsite location table
			$masterID = $event->ID;	
			$eventIDs = $events_db->get_results(
				"
				SELECT gc_event_id, gc_event_location_id
				FROM  gc_events_onsite_locations
				WHERE gc_event_id = '$masterID'
				"
			);
			// Store it in an array so we can use the location ID's to find out the names
			$locationID = array();
			foreach ($eventIDs as $eventID) :
				$locationID[] = $eventID->gc_event_location_id;
			endforeach;
			
			// Join the ID's with a comma so we can use them in the query
			$locationIDs = join(',',$locationID);
			
			// If there is no value in the array, default to 0 so we don't get an error
			if (empty($locationIDs)) {
				$locationIDs = 0;
			}
			
			// Use location ID's to find out the name of each one
			$locations = $events_db->get_col(
				"
				SELECT DISTINCT location
				FROM  gc_events_locations
				WHERE ID IN ($locationIDs)
				"
			);
			// Build an array of all locations queried
			$locationNames = array();
			foreach ($locations as $location) :
				$locationNames[] = $location;
			endforeach;
			
			// Display a comma seperated list of the values
			echo join(', ',$locationNames);
		};
		echo '<hr>';
	endforeach;
}
add_shortcode('events_db', 'events_db_shortcode');