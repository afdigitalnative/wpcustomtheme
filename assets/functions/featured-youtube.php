<?php
function latest_youtube($user) {
error_reporting(E_ALL);
$feedURL = 'https://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?max-results=1';
$sxml = simplexml_load_file($feedURL);
$i=0;
foreach ($sxml->entry as $entry) {
      $media = $entry->children('media', true);
      $url = (string)$media->group->player->attributes()->url;
      $thumbnail = (string)$media->group->thumbnail[0]->attributes()->url;
?>
<video controls="" width="640" height="360" poster="<?php echo $thumbnail;?>">
	<source src="<?php echo $url; ?>" type="video/youtube" />
</video>    
<?php $i++; }
} ?>




<?php

// Featured Youtube Video
function youtube_feed_shortcode($atts) {
    // Defaults:
    extract(shortcode_atts(array(
            'user' => 'revivaltv', // youtube user
            'limit' => 1, // maximum number of videos
            'height' => 167, // video height
            'width' => 250 // video width
        ), $atts));
    $data = @json_decode(file_get_contents('https://gdata.youtube.com/feeds/api/users/'.$user.'/uploads?alt=json'), TRUE);
    $counter = 0;
    $content = '<div class="youtubefeed">';
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

?>