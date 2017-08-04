<?php get_header(); ?>

		<div class="full-grey title-wrap">
			<h1 class="col-12">Search</h1>
			<div class="col-10">
				<p><?php echo 'Results for: ' . get_search_query(); ?></p>
			</div>
			<div id="layout-controls" class="col-2 group">
				<a href="#" class="list">l</a>
				<a href="#" class="grid">m</a>
			</div>		
		</div>
		
		<?php if (have_posts()) : ?>
			<ul id="archives" class="grid">
				<?php while (have_posts()) : the_post(); ?>
					<li>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
					<?php the_post_thumbnail(); ?>
					</a>
					<h3>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
					<?php the_title_limit( 26, '...'); ?>
					</a>
					</h3>
					<small><?php the_time('F j, Y'); ?></small>
					<div class="excerpt">
					<?php $more_text = 'Continue Reading '; the_excerpt() ?>
					</div>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php pagination(); ?>
		<?php else : ?>
			<h2 class="col-12">No posts found.</h2>
		<?php endif; ?>

<?php get_footer(); ?>