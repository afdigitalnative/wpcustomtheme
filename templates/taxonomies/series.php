<?php get_header();

//get the taxonomy slug
$slug = get_query_var('term');

//get the current taxonomy_id
$term = get_term_by( 'slug', $slug,  'series' );

//get the term_id
$term_id = $term->term_id;

//get term description
$term_desc = term_description( '', get_query_var( 'taxonomy' ) );

//check depth
$args= array(
	'hide_empty'=> 0,  
	'parent'	=> $term_id,
	'taxonomy'	=> 'series'
);

// store categories in variable
$categories = get_categories($args);
?>

<div class="full-grey title-wrap">	
	<h1 class="col-12" <?php if($categories) { ?> style="margin: 1.25%;"<?php } ?>><?php echo $term->name; ?></h1>
	<div class="col-10">
		<?php if($term_desc != '') { ?>
			<?php echo $term_desc; ?>
		<?php } ?>
	</div>
	<?php if(!$categories) { ?>
		<div id="layout-controls" class="col-2 group">
			<a href="#" class="list">l</a>
			<a href="#" class="grid">m</a>
		</div>
	<?php } ?>
</div>

<?php
//If lowest level display posts
if(!$categories) { ?>
	<ul id="archives" class="grid">
	<?php global $query_string; query_posts( $query_string . '&order=ASC' ); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $post_meta_data = get_post_custom('post_type=sermons'); ?>
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
	<?php pagination(); ?>
	<?php else: ?>
		<p class="col-12">Sorry, no posts were found.</p>
	<?php endif; 
}

//If NOT lowest level display term
else {
	$terms = apply_filters( 'taxonomy-images-get-terms', '', array('having_images' => false, 'taxonomy' => 'series', 'term_args' => $args) );
		
	if ( !empty( $terms ) ) {
	echo '<ul id="archives" class="grid">';
		foreach( $terms as $term ) {
			echo '<li>';
			echo '<a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '">' . wp_get_attachment_image( $term->image_id, 'full' ) . '</a><div class="info">' . 
			'<h3><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '" ' . '>' . $term->name.'</a></h3></div>';
			echo '</li>';
		}
	echo '</ul>';
	} 
}
?>

<?php //pagination(); ?>

<?php if(function_exists('bcn_display')) { ?>
	<div id="breadcrumbs">
	<?php bcn_display(); ?>
	</div>
<?php } ?>

<?php get_footer(); ?>