<?php 
get_header();

// Get media term id
$media = get_term_by('name', 'media', 'series');

// Query terms for later use below
$terms = apply_filters( 'taxonomy-images-get-terms', '', array('having_images' => false, 'taxonomy' => 'series', 'term_args' => array('hide_empty' => 0, 'exclude' => array($media->term_id), 'parent' => 0,)) );
?>

<div id="media-title" class="full-dark title-wrap">
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
		'posts_per_page' => 1
	);
	?>
	<?php $archiveLoop = new WP_Query($args); while ($archiveLoop->have_posts()) : $archiveLoop->the_post(); ?>
	<?php $post_meta_data = get_post_custom('post_type=sermons'); ?>
	<?php $sermon_series = get_the_term_list( $post->ID, 'series'); ?>
	
	<?php // MEDIA BEGIN ?>
	<div class="col-8">
		<div id="media-wrap">
			<?php display_sermon_media(); ?>
		</div>			
	</div>
	
	<div class="col-4 aside">
		<h2 class="latest-sermon">Latest Sermon</h2>
		<h2>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<div>
			<span>Date:</span> <?php the_date(); ?>
		</div>
		<div>
			<span>Speaker:</span>
			<?php if(!isset($post_meta_data['sermon_guest'])) { ?>
	 			<?php echo the_author_posts_link(); ?>
	 		<?php } else if(isset($post_meta_data['sermon_guest'])) { ?>
	 			<?php echo $post_meta_data['sermon_guest'][0]; ?>
	 		<?php } ?>
		</div>
		<div>
			<span>Description:</span> <?php echo limit_words(get_the_excerpt(), '32'); ?>
		</div>
		<div>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="btn">View Full Sermon</a>
		</div>
	</div>
</div>
	<?php endwhile; wp_reset_postdata(); ?>

	<?php if ( !empty( $terms ) ) {
	echo '<ul id="archives" class="grid group">';
		foreach( $terms as $term ) {
			echo '<li>';
			echo '<a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '">' . wp_get_attachment_image( $term->image_id, 'full' ) . '</a><div class="info">' . 
			'<h3><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '" ' . '>' . $term->name.'</a></h3></div>';
			echo '</li>';
		}
	echo '</ul>';
	} ?>

<?php get_footer(); ?>