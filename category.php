<?php get_header(); ?>

	<div class="full-grey title-wrap">
		<h1 class="col-12">Category: <?php wp_title(''); ?></h1>
	</div>

	<div class="col-9">
	<?php
	$categories = get_the_category();
	$separator = ', ';
	$output = '';
	if($categories) {
		foreach($categories as $category) {
			$output .= '<a href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
	}
	?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>

			<div class="entry group">
				
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); ?>
				</a>
				
				<div class="date">
					<span><?php the_time('M'); ?></span>
					<span><?php the_time('d'); ?></span>
					<span><?php the_time('Y'); ?></span>
				</div>
				
				<div class="post-title">
				<h2>
					<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h2>
					<span class="posted">
						<?php echo 'Posted by: '; the_author_posts_link(); ?>
					</span>
				<?php if($categories) { ?>
					<span class="posted">
						<?php echo 'in: ' . trim($output, $separator); ?>
					</span>
				<?php } ?>
				</div>
				 
				<?php $more_text='Continue Reading '; the_excerpt(); ?>
				
				<?php the_tags( 'Tags: ', ', ', ''); ?>

			</div>
			
		</div>
		<?php endwhile; ?>
	<?php else : ?>
		<h2 class="col-12">No posts found.</h2>
	<?php endif; ?>
	<?php pagination(); ?>
	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>