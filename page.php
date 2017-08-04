<?php get_header(); ?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
	<div class="full-grey page-title-wrap">
		<?php if ( has_post_thumbnail() ) { ?>
			<div id="banner">
				<h1 id="ministry-title">
					<?php the_title(); ?>
				</h1>
				<?php echo get_the_post_thumbnail(); ?>
			</div>
		<?php } else { ?>
			<h1 class="col-12"><?php the_title(); ?></h1>
		<?php } ?>
	</div>
	
	<div class="col-12">
	
		<?php the_content(); ?>
	
	</div>
	
	<div class="col-12">
		<?php edit_post_link('Edit this page.', '<p>', '</p>'); ?>
	</div>
	
	<?php endwhile; endif; ?>

<?php get_footer(); ?>