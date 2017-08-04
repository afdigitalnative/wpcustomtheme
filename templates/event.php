<?php /* Template Name: Event */ 

$post_meta_data = get_post_custom('post_type=page');

function event_accent(){
	global $post, $post_meta_data;
	if(isset($post_meta_data['event_color'])) {
		$color = $post_meta_data["event_color"][0];
	} else {
		$color = "00a40c";
	}
		$colorDark = colorBrightness($color,-.60);
		$colorDarker = colorBrightness($color,-.50);
		$colorLight = colorBrightness($color,.25);
?>
<style>
	a:hover, a:active, .tab-title {
		color: #<?php echo $color ?>;
	}
	.tab-nav {
		border-bottom: 4px solid #<?php echo $color ?>;
	}
	.current:before {
		color: #<?php echo $color ?> !important;
	}
	<?php if(function_exists('hex2rgb')) { ?>
	input[type="text"]:focus, textarea:focus {
		border-color: <?php hex2rgb($color, 0.5); ?> !important;
	}
	<?php } ?>
	.oe-contact-form input[type="submit"] {
		border-color: #<?php echo $colorDark ?>;
		background: #<?php echo $color ?>;
	}
	#event-header {
		background: #<?php echo $colorDarker ?>;
	}
	a.readmore, #mc-form input[type="submit"]:hover, a.readmore:hover {
		background: #<?php echo $color ?>;
	}
	#event-header, .sidebar-title {
		color: #<?php echo $colorLight ?>;
	}
	.thumbnail:hover {
		border-color: #<?php echo $color ?>;
	}
	.countdown > span:not(:last-child), a.readmore, a.readmore:hover {
		border-color: #<?php echo $colorDark ?>;
	}
	.countdown .dd, [class*="hh-"], [class*="mm-"], [class*="ss-"] {
		text-shadow: 1px 1px 0px #<?php echo $colorDark ?>;
	}
	.sidebar-title, .oe-contact-form input[type="submit"]:hover {
		border-color: #<?php echo $colorDark ?>;
		background: #<?php echo $color ?>;
	}
	.countdown .label, .sharrre span, .sharrre:not(:nth-last-child(2)):after {
		color: #<?php echo $color ?>;
		border-color: #<?php echo $color ?>;
	}
	.sidebar-title:after {
		border-color: transparent transparent transparent #<?php echo $color ?>;
	}
	#contact a, #contact-wrap a, #tabs h2 {
		color: #<?php echo $color ?>;
	}
</style>
<?php
}
add_action('wp_head', 'event_accent');

?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php // MEDIA BEGIN ?>
<div class="full-dark">	
	<div id="banner">
		<h1 id="ministry-title">
			<?php the_title(); ?>
		</h1>
		<?php echo get_the_post_thumbnail(); ?>
	</div>			
</div>

<?php // EVENT HEAD BEGIN ?>
<div id="event-header" class="group">
	<div class="col-12">
		<span class="sidebar-title">This Event Starts In</span><time class="countdown"><?php echo $post_meta_data["event_date"][0];?></time>
		<?php sharrre(); ?>
	</div>
</div>

<?php // TABS BEGIN ?>			
<div id="tabs" class="group">    			
	<ul class="tab-nav group">
        <?php 
        	
        	if( isset( $post_meta_data["event_form"][0] ) ) {
        		$form = $post_meta_data["event_form"][0];
        	} else {
	        	$form = '';
        	}
        	
        ?>
        
        <li><a href="#about" class="tab-4 current">About</a></li>
        <?php if (substr($form, 0, 1) === '[' || substr($form, 0, 4) === 'http') { ?>
        <li><a href="#tickets" class="tab-4">Tickets</a></li>
        <? } else { ?>
        <li><a href="#location" class="tab-4" onclick="load()">Location</a></li>
        <?php } ?>
        <li><a href="#updates" class="tab-4">Updates</a></li>
        <li><a href="#contact" class="tab-4">Contact</a></li>
    </ul>
	
	<?php // CONTENT BEGIN ?>
	<div class="list-wrap col-12 group">	
		 
		 <?php // DESCRIPTION ?>
		 <section id="about">
		 	<?php the_content(); ?>
		 </section>
		 
		 <?php // TICKETS ?>
		 <?php
		 if (substr($form, 0, 1) === '[') {
		 	echo '<section id="tickets" class="hide">';
			 echo do_shortcode("$form");
			echo '</section>';
		 } else if (substr($form, 0, 4) === 'http') {
		 	echo '<section id="tickets" class="hide">';
			 echo '<iframe src="'.$form.'" frameborder="0" width="80%" height="950" scrolling="no"></iframe>';
			echo '</section>';
		 } else {
		 	
		 	$contact_page = get_page_by_title( 'Contact' );
		 	$contact_meta = get_post_custom($contact_page->ID);
		 
		    echo '<section id="location" class="hide group">';
		    ?>
		    	<div id="contact-wrap" class="col-6">
					<h2>Visit Revival</h2>
					
					<div class="col-6">
					<?php if(isset($contact_meta['contact_name']) && isset($contact_meta['contact_address']) && isset($contact_meta['contact_city_state_zip'])) { ?>
						<h3>Location</h3>
						<ul>
						<li><?php echo $contact_meta['contact_name'][0]; ?></li>
						<li><?php echo $contact_meta['contact_address'][0]; ?></li>
						<li><?php echo $contact_meta['contact_city_state_zip'][0]; ?></li>
						</ul>
						<a href="https://maps.google.com/maps?q=<?php echo str_replace(' ','+',$address); ?>" target="_blank">Get Directions &raquo;</a>
					<?php } ?>
					</div>
					
					<div class="col-6">
					<?php if(isset($contact_meta['contact_phone'][0]) || isset($contact_meta['contact_fax'][0]) || isset($contact_meta['contact_email'][0])) { ?>
						<h3>Event Times</h3>
						<ul>
						<?php
						$daysofweek = array('Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat');
						
						$n = 0;
						
						foreach( $daysofweek as $day ) {
							
							if( isset( $post_meta_data['event_times_'.$n][0] ) ) {
								echo '<li><strong>' . $day . ':</strong> ' . $post_meta_data['event_times_'.$n][0] . '</li>';
							}
							
							$n++;
						}
						?>
						</ul>
					<?php } ?>
					</div>
				</div>
				
				<div class="col-6">
					<h2>Map</h2>
					<div id="map"></div>
				</div>
		    <?php
		    echo '</section>';
		 }
		 ?>
		
		 <?php // BLOG ?>
		 <section id="updates" class="hide">
		 	<ul>
		 	<?php
		 	// Creates a slug friendly title
		 	$safeTitle = urlSafeTitle();
		
		 	// Check if any blog posts
		 	$eventArgs = 'category_name='.$safeTitle.'-event&showposts=3';
		 	
		 	$eventargs = array(
				'tax_query' => array(
				array(
					'field'     => 'slug',
					'terms'     => array('radio-messages', 'media')
					)
				),
				'posts_per_page' => 3
			);
		 	$eventPost = new WP_Query($eventArgs);

			if ($eventPost->have_posts()) {
				while ($eventPost->have_posts()) { $eventPost->the_post();
					?>
					<li class="group">
						<span class="thumbnail">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail('thumbnail'); ?>
							</a>
						</span>
						<span class="content">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<h4 class="tab-title">
									<?php the_title(); ?>
								</h4>
							</a>
							<span class="posted">
								<?php the_date('M, d, Y'); ?>
							</span>
							<?php $more_text='Continue Reading '; the_excerpt(); ?>
						</span>
					</li>
					<?php
				}
			} else {
				echo "Sorry no posts found, please check back soon.";
			}
			wp_reset_postdata(); ?>
		 	</ul>
		 </section>
		 
		 <?php // CONTACT ?>
		 <section id="contact" class="hide">
		 	<?php
		 	if(isset($post_meta_data['event_email'])) { 
		 		$email = $post_meta_data['event_email'][0];
		 		echo do_shortcode('[contact email="'.$email.'"]');
		 	}
		 	?>
		 </section>
	 </div>
</div>
<?php // TABS END ?>

<?php endwhile; ?>
<?php endif; ?>

<?php if(function_exists('edit_post_link')) { ?>
	<?php echo '<div class="col-12">'; edit_post_link('Edit This Event'); echo '</div>'; ?>
<?php } ?>

<?php get_footer(); ?>