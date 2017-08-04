<?php get_header(); $post_meta_data = get_post_custom('post_type=sermons'); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); setPostViews(get_the_ID()); ?>
<?php $sermon_series = get_the_term_list( $post->ID, 'series'); ?>
<?php add_filter( 'posts_where', 'post_password_filter' ); ?>

<?php // MEDIA BEGIN ?>
<div class="full-dark">	
	<div id="banner">
		<h1 id="ministry-title">
			<?php the_title(); ?>
		</h1>
		<?php echo get_the_post_thumbnail(); ?>
	</div>			
</div>

<?php // TABS BEGIN ?>			
<div id="tabs" class="group">    			
	<ul class="tab-nav group">
		<?php
		// Creates a slug friendly title
		$safeTitle = urlSafeTitle();
		
		// Check if any blog posts
		$ministryArgs = 'category_name='.$safeTitle.'&showposts=5';
		$ministryQuery = new WP_Query($ministryArgs);
		if ($ministryQuery->have_posts()) {
			$blog = 'Blog,';
		} else {
			$blog = '';
		}
		wp_reset_postdata();
		
		// Check if any media posts
		$mediaArgs = array(
		    'post_type' => 'sermon',
		    'post_status' => 'publish',
		    'tax_query' => array(
		        array(
		            'taxonomy' => 'series',
		            'field' => 'slug',
		            'terms' => $safeTitle.'-media'
		        )
		    )
		); 
		$mediaQuery = new WP_Query($mediaArgs);
		if ($mediaQuery->have_posts()) {
			$media = 'Media,';
		} else {
			$media = '';
		}
		wp_reset_postdata();
		
		createTabs($blog . 'About,Calendar,'. $media .'Contact');
		?>
    </ul>
	
	<?php // CONTENT BEGIN ?>
	<div class="list-wrap col-9 group">	
		 
		 <?php // BLOG ?>
		 <?php
		 $ministryQuery = new WP_Query($ministryArgs);
		 if ($ministryQuery->have_posts()) {
		 ?>
		 <section id="blog" class="group">
		 	<ul>
		 	<?php
				while ($ministryQuery->have_posts()) { $ministryQuery->the_post();
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
			?>
		 	</ul>
		 	<?php
		 	
		 	// Get the ID of a given category
		    $category_id = get_cat_ID( $safeTitle );
		
		    // Get the URL of this category
		    $category_link = get_category_link( $category_id );
		 	?>
		 	<a href="<?php echo esc_url( $category_link ); ?>" title="View All">View All Posts</a>
		 </section>
		 <?php } wp_reset_postdata(); ?>
		 
		 <?php // DESCRIPTION ?>
		 <section id="about" class="<?php if($blog != '') echo "hide"; ?> group">
         	<?php the_content(); ?>
		 </section>
		 
		 <?php // CALENDAR ?>
		 <section id="calendar" class="hide group">
			<?php
		 	$ministry = (isset($post_meta_data['ministry_calendar'])) ? $post_meta_data['ministry_calendar'][0] : 0;
		 	echo do_shortcode('[events_calendar ministry="'.$ministry.'"]');
		 	?>
		 </section>
		 
		<?php // MEDIA ?>
		 <?php 
		 $mediaQuery = new WP_Query($mediaArgs);
		 if ($mediaQuery->have_posts()) {
		 ?>
		 <section id="media" class="hide group">
		 	<ul>
		 	<?php
				while ($mediaQuery->have_posts()) { $mediaQuery->the_post();
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
						<?php $more_text='View Sermon '; the_excerpt(); ?>
					</span>
				</li>
				<?php
				}
			?>
		 	</ul>
		 </section>
		 <?php } wp_reset_postdata(); ?>
		 
		 <?php // CONTACT ?>
		 <section id="contact" class="hide group">
		 	<?php
		 	if(isset($post_meta_data['ministry_email'])) { 
		 		$email = $post_meta_data['ministry_email'][0];
		 		echo do_shortcode('[contact email="'.$email.'"]');
		 	}
		 	?>
		 </section>
	 </div>

	<div class="col-3">	 
		<table id="side-ministry">
		  <?php if(isset($post_meta_data['ministry_pastor'])) { ?>
		  <tr>
		    <td>Pastor</td>
		    <td><?php userLink($post_meta_data['ministry_pastor'][0]); ?></td>
		  </tr>
		  <?php } if(isset($post_meta_data['ministry_leader'])) { ?>
		  <tr>
		    <td>Leader</td>
		    <td><?php userLink($post_meta_data['ministry_leader'][0]); ?></td>
		  </tr>
		  <?php } if(isset($post_meta_data['ministry_when'])) { ?>
		  <tr>
		    <td>When</td>
		    <td><?php echo $post_meta_data['ministry_when'][0]; ?></td>
		  </tr>
		  <?php } if(isset($post_meta_data['ministry_where'])) { ?>
		  <tr>
		    <td>Where</td>
		    <td><?php echo $post_meta_data['ministry_where'][0]; ?></td>
		  </tr>
		  <?php } if(isset($post_meta_data['ministry_facebook']) || isset($post_meta_data['ministry_twitter'])) { ?>
		  <tr>
		    <td>Social</td>
		    <td>
				<?php if(isset($post_meta_data['ministry_facebook'])) { ?>
					<a href="https://www.facebook.com/<?php echo $post_meta_data['ministry_facebook'][0]; ?>" target="_blank" class="social">F</a>
			    <?php } if(isset($post_meta_data['ministry_twitter'])) { ?>
					<a href="https://twitter.com/<?php echo $post_meta_data['ministry_twitter'][0]; ?>" target="_blank" class="social">T</a>
				<?php } ?>
		    </td>
		  </tr>
		  <?php } ?>
		</table>
		
		<aside id="aside-ministry">
			<?php
			$cat_1 = get_category_id(urlSafeTitle());
			$cat_2 = get_category_id('registration');
			
			$regQuery = array(
			'showposts' => 3,
			'category__and' => array($cat_1, $cat_2)
			);
			$regPost = new WP_Query($regQuery);
			if ($regPost->have_posts()) { 
				?>
				<span class="tab-title">Upcoming Events</span>
				<ul id="recent-posts">
				<?php
				while ($regPost->have_posts()) { $regPost->the_post();
				$meta = get_post_custom(); $prefix = 'post_event_';
					?>
					<li>
						<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="thumbnail">
						<?php the_post_thumbnail('thumbnail'); ?> 
						</a>
						<h4 class="recent-title">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
						<?php the_title(); ?>
						</a>
						<?php if(isset($meta[$prefix.'date'])) { ?>
							<span><?php echo $meta[$prefix.'date'][0]; ?></span>
						<?php } else { ?>
							<span><?php the_time('F d, Y'); ?></span>
						<?php } ?>
						</h4>
					</li>
					<?php
				}
				?>
				</ul>
			<?php } wp_reset_postdata(); ?>
		</aside>
	</div>

</div>
<?php // TABS END ?>

<?php endwhile; ?>
<?php endif; ?>

<?php if(function_exists('edit_post_link')) { ?>
	<?php echo '<div class="col-12">'; edit_post_link('Edit This Ministry'); echo '</div>'; ?>
<?php } ?>

<?php remove_filter( 'posts_where', 'post_password_filter' ); ?>
<?php get_footer(); ?>