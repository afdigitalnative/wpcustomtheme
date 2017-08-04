<?php get_header(); ?>

<div id="media-title" class="full-dark">
	
	<?php // MEDIA BEGIN ?>
	<div id="youtube-video" class="col-8">
		<div id="media-wrap">
			<?php echo latest_youtube('revivaltv'); ?>
		</div>
	</div>
	
	<div id="upcoming-events" class="col-4 aside">
		<h2>
			This Week At Revival
		</h2>
		<?php $archiveLoop = new WP_Query('category_name=youtube&posts_per_page=3'); ?>
		<?php if($archiveLoop->have_posts()) { ?>
		<ul id="recent-posts">
		<?php while ($archiveLoop->have_posts()) : $archiveLoop->the_post(); ?>
			<?php $meta = get_post_custom(); $prefix = 'post_event_'; ?>
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
		<?php endwhile; wp_reset_postdata(); ?>
		</ul>
		<?php } else { ?>
			<p>No upcoming events at this time. Please check back later.</p>
		<?php } ?>
	</div>
</div>

<?php // TABS BEGIN ?>			
<div id="tabs" class="group">    			
	<ul class="tab-nav group">
		<?php
		createTabs('Ministries,Calendar,Blog,Registration');
		?>
    </ul>
	
	<?php // CONTENT BEGIN ?>
	<div class="list-wrap col-12 group">	
		 
		 <?php // MINISTRIES ?>
		 <section id="ministries" class="group">
         	<?php
			echo '<ul id="archives" class="grid group">';
				query_posts('post_type=ministry&posts_per_page=16&orderby=title&order=ASC');
				if (have_posts()) : while (have_posts()) : the_post();
					?>
					<li>
						<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail($id, 'sliderimg'); ?></a>
						<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><div class="info"><h3><?php the_title_limit( 26, '...'); ?></h3></div></a>
					</li>
					<?php
				endwhile; endif;
			echo '</ul>';
			?>
		 </section>
		 
		 <?php // CALENDAR ?>
		 <section id="calendar" class="hide group">
			 <?php echo do_shortcode('[events_calendar]'); ?>
		 </section>
		
		 <?php // BLOG ?>
		 <?php 
		 $ministryQuery = 'showposts=5'; $ministryPost = new WP_Query($ministryQuery);
		 if ($ministryPost->have_posts()) {
		 ?>
		 <section id="blog" class="hide group">
		 	<ul>
		 	<?php
				while ($ministryPost->have_posts()) { $ministryPost->the_post();
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
		 </section>
		 <?php } wp_reset_postdata(); ?>
		 
		 <?php // REGISTRATION ?>
		 <?php $regLoop = new WP_Query('category_name=registration&posts_per_page=5'); ?>
		 <?php if($regLoop->have_posts()) { ?>
		 <section id="registration" class="hide group">
		 	<ul>
		 	<?php
				while ($regLoop->have_posts()) { $regLoop->the_post();
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
						<?php $more_text='Register '; the_excerpt(); ?>
					</span>
				</li>
				<?php
				}
			?>
		 	</ul>
		 </section>
		 <?php } wp_reset_postdata(); ?>
	 </div>

	</div>

</div>
<?php // TABS END ?>

<?php get_footer(); ?>