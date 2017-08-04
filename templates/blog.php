<?php /* Template Name: Blog */ ?>
<?php
function authorLink() {
	global $leader_id;
	echo '<a href="'.get_permalink($leader_id).'">';
	echo get_the_title($leader_id);
	echo '</a>'; 
} ?>
<?php get_header(); ?>
	<div class="col-9">
<?php

/*add_filter( 'posts_where', 'post_password_filter' );*/ //PROTECTED POST FILTER

$temp = $wp_query;
$wp_query= null;
$wp_query = new WP_Query();
$wp_query->query('posts_per_page=10'.'&paged='.$paged);
while ($wp_query->have_posts()) : $wp_query->the_post();
$post_meta_data = get_post_custom('post_type=post');

$categories = get_the_category();
$separator = ', ';
$output = '';
if($categories) {
	foreach($categories as $category) {
		$output .= '<a href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
	}
}
?>
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

				<?php //edit_post_link('Edit this entry','',''); ?>

			</div>
			
		</div>

	<?php endwhile; wp_reset_postdata(); remove_filter( 'posts_where', 'post_password_filter' ); ?>
	<?php pagination(); ?>
	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>