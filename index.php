<?php get_header(); ?>

<section class="sliderWrapper group">
	<div class="slider">
		<ul class="slides">
			<?php echo do_shortcode("[oeSlider]"); ?>
		</ul>
	</div>
</section>

<section class="full-home">
	<ul id="archives" class="grid">
	<?php
	$args = array( 
		'post_type'  => 'sermon',
		'tax_query' => array(
		array(
			'taxonomy'  => 'series',
			'field'     => 'slug',
			'terms'     => array('radio-messages', 'media'),
			'operator'  => 'NOT IN'
			)
		),
		'posts_per_page' => 4
	);
	?>
	<?php $featLoop = new WP_Query($args); while ($featLoop->have_posts()) : $featLoop->the_post(); ?>
	<?php $post_meta_data = get_post_custom(); ?>
		<li>
		  <a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="hvr" >
		    <?php the_post_thumbnail('medium'); ?>
		  </a>
		  <div class="info">
				<h3>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
						<?php the_title_limit( 26, '...'); ?>
					</a>
				</h3>

				<?php if( isset($post_meta_data['sermon_passage'][0]) || isset($post_meta_data['sermon_start_verse'][0]) ) { ?>
					<small class="verse">
						<?php echo $post_meta_data['sermon_passage'][0]; ?>
						<?php echo $post_meta_data['sermon_start_verse'][0]; ?>
						<?php if( isset($post_meta_data['sermon_end_verse'][0]) ) { ?>
							<?php echo ' - '.$post_meta_data['sermon_end_verse'][0]; ?>
						<?php } ?>
					</small>
				<?php } ?>
				
				<small class="time">
					<?php the_time('M j, Y'); ?>
				</small>
				
				<div class="excerpt">
					<?php $more_text='View Sermon '; the_excerpt(); ?>
		  		</div>
		  </div>
		</li>
	<?php endwhile; ?>
	</ul>
</section>

<section class="updates group">

	<?php
	$radio_args = array( 
		'post_type'  => 'sermon',
		'tax_query' => array(
		array(
			'taxonomy'  => 'series',
			'field'     => 'slug',
			'terms'     => 'radio-messages'
			)
		),
		'posts_per_page' => 1
	);
	?>

	<?php query_posts($radio_args); while (have_posts()) : the_post(); ?>
	<div class="col-4">
		<h4>Radio</h4>
			<h5><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>

				<?php $more_text='Listen Now '; the_excerpt(); ?>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>
	
	<?php query_posts('showposts=1&post_type=devotion'); while (have_posts()) : the_post(); ?>
	<div class="col-4">
		<h4>Devotion</h4>
			<h5><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>

				<?php $more_text='Continue Reading '; the_excerpt(); ?>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>
	
	<?php query_posts('showposts=1&category_name=featured'); while (have_posts()) : the_post(); ?>
	<div class="col-4">
		<h4>Blog</h4>
			<h5><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>

				<?php $more_text='Continue Reading '; the_excerpt(); ?>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>
</section>

<?php get_footer(); ?>