<?php

// Event Shortcode
function event_shortcode() {
	$meta = get_post_custom();
	$prefix = 'post_event_';
	
	$e  = '<p>';
	if(isset($meta[$prefix.'date'])) { $e .= '<strong>Date:</strong> '.$meta[$prefix.'date'][0].'<br />'; }
	if(isset($meta[$prefix.'time'])) { $e .= '<strong>Time:</strong> '.$meta[$prefix.'time'][0].'<br />'; }
	if(isset($meta[$prefix.'cost'])) { $e .= '<strong>Cost:</strong> '.$meta[$prefix.'cost'][0].'<br />'; }
	if(isset($meta[$prefix.'location'])) { $e .= '<strong>Location:</strong> '.$meta[$prefix.'location'][0].'<br />'; }
	$e .= '</p>';
	
	return $e;
}
add_shortcode('event', 'event_shortcode');

// Featured Youtube Video
function youtube_feed_shortcode($atts) {
    // Defaults:
    extract(shortcode_atts(array(
            'user' => 'revivaltv', // youtube user
            'limit' => 1, // maximum number of videos
            'height' => '100%', // video height
            'width' => '100%' // video width
        ), $atts));
    $data = @json_decode(file_get_contents('https://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?alt=json'), TRUE);
    $counter = 0;
    $content = '<div class="video-container">';
    foreach($data['feed']['entry'] as $vid)
    {
        $url = $vid['media$group']['media$content'][0]['url'];
        $title = $vid['title']['$t'];
        $ycontent = $vid['content']['$t'];
        $content.= '<object width="'.$width.'" height="'.$height.'">'.
            '<param name="movie" value="'.$url.'"></param>'.
            '<param name="allowFullScreen" value="true"></param>'.
            '<param name="allowscriptaccess" value="always"></param>'.
            '<embed src="'.$url.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>'."\n";
        $counter++;
        if($counter == $limit)
        {
            break;
        }
    }
    $content .= '</div>';
    return $content;
}
add_shortcode('youtubefeed', 'youtube_feed_shortcode');

// Latest Youtube Video
function latest_youtube($user, $limit = 1) {
	$feedURL = 'https://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?max-results='.$limit;
	$sxml = simplexml_load_file($feedURL);
	$i=0;
	foreach ($sxml->entry as $entry) {
		$media = $entry->children('media', true);
		$url = (string)$media->group->player->attributes()->url;
		$thumbnail = (string)$media->group->thumbnail[0]->attributes()->url;
		$e  = '<div id="video-wrap">';
		$e .= '<video controls="" width="640" height="360">';
		$e .= '<source src="'.$url.'" type="video/youtube" />';
		$e .= '</video>';
		$e .= '</div>';    
	}
	$i++;
	return $e;
}

// Video Shortcode
function video_shortcode($atts) {
 
	extract(shortcode_atts(array(
	    'url' => '',
	    'format' => 'mp4',
	), $atts));

	echo '<video controls="" width="640" height="360">';
	echo '<source src="'.$url.'" type="video/'.$format.'" />';
	echo '</video>';
}
add_shortcode('video', 'video_shortcode');

// Add Shortcode Button for Video
function register_video_button( $buttons ) {
   array_push( $buttons, "video" );
   return $buttons;
}
function add_video_plugin( $plugin_array ) {
   $plugin_array['video'] = get_template_directory_uri() . '/js/MCE-video.js';
   return $plugin_array;
}

// Audio Shortcode
function audio_shortcode($atts) {

	extract(shortcode_atts(array(
	    'url' => ''
	), $atts));

	echo '<audio controls="" preload="none" width="640" height="30" src="'.$url.'"></audio>';
}
add_shortcode('audio', 'audio_shortcode');

// Add Shortcode Button for Audio
function register_audio_button( $buttons ) {
   array_push( $buttons, "audio" );
   return $buttons;
}
function add_audio_plugin( $plugin_array ) {
   $plugin_array['audio'] = get_template_directory_uri() . '/js/MCE-audio.js';
   return $plugin_array;
}

// Initiate MCE Buttons
function custom_mce_buttons() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }
   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'add_video_plugin' );
      add_filter( 'mce_external_plugins', 'add_audio_plugin' );
      add_filter( 'mce_buttons', 'register_video_button' );
      add_filter( 'mce_buttons', 'register_audio_button' );
   }
}
add_action('init', 'custom_mce_buttons');