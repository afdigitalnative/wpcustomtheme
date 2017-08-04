<?php get_header(); ?>

		<div class="full-grey title-wrap">
			<?php $allsearch = &new WP_Query("s=$s&showposts=-1"); ?>
				<h1 class="col-12">
					<?php 
					if ( is_post_type_archive() ) {
						post_type_archive_title();
					} else {
						echo 'Term: '; single_term_title();
					}
					?>
				</h1>
			<?php wp_reset_query(); ?>
		</div>
		
		<?php if (have_posts()) : ?>
			<ul id="archives" class="grid">
				<?php while (have_posts()) : the_post(); ?>
					<li>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="hvr" >
					<?php the_post_thumbnail(); ?>
					</a>
					<h3>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
					<?php the_title_limit( 26, '...'); ?>
					</a>
					</h3>
					<small></small>
					<div class="excerpt">
					<?php the_excerpt() ?>
					</div>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php pagination(); ?>
		<?php else : ?>
			<h2 class="col-12">No posts found.</h2>
		<?php endif; ?>

<?php get_footer(); ?>