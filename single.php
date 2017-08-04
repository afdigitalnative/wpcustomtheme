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
<?php get_header(); ?>
	<div class="col-9">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>

			<div class="entry">
				
				<?php the_post_thumbnail(); ?>
				
				<div class="post-head group">
					<div class="date">
						<span><?php the_time('M'); ?></span>
						<span><?php the_time('d'); ?></span>
						<span><?php the_time('Y'); ?></span>
					</div>
					
					<div class="post-title">
					<h2><?php the_title(); ?></h2>
						<span class="posted">
							<?php echo 'Posted by: '; the_author_posts_link(); ?>
						</span>
					<?php if($categories) { ?>
						<span class="posted">
							<?php echo 'in: ' . trim($output, $separator); ?>
						</span>
					<?php } ?>
					</div>
					
					<?php sharrre(); ?>
				</div>
				
				<?php the_content(); ?>
				
				<div id="post-nav" class="group">
					<span class="post-prev"><?php previous_post_link('%link', '&#171; Prev Post'); ?></span>
					<span class="post-next"><?php next_post_link('%link', 'Next Post &#187;'); ?></span>
				</div>
				
				<?php edit_post_link('Edit this entry','',''); ?>

			</div>
		</div>
		
	<?php endwhile; endif; ?>
	
	<?php comments_template(); ?>
	
	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>