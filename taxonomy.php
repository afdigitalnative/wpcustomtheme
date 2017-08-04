<?php 
get_header(); 
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$termDesc = term_description( '', get_query_var( 'taxonomy' ) );
?>

  	<div class="full-grey">
  	<div class="col-12">	
  		<h1 class="title"><?php echo $term->name; ?></h1>
  		<?php if($termDesc != '') : ?>
	  		<div class="term-desc"><?php echo $termDesc; ?></div>
	  	<?php endif; ?>
  		<div id="layout-controls" class="group">
			<a href="#" class="grid">m</a>
			<a href="#" class="list">l</a>
		</div>
  	</div>
  	</div>
  		
	<ul id="archives" class="grid">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li>
	  <a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="hvr" >
	    <?php the_post_thumbnail(); ?>
	  </a>
	  <h3>
	  	<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
	  		<?php the_title_limit( 26, '...'); ?>
	  	</a>
	  </h3>
	  <small><?php the_time('F j, Y'); ?></small>
	</li>
	<?php endwhile; endif; ?>
	</ul>

	<?php pagination(); ?>
	
	<?php if(function_exists('bcn_display')) { ?>
		<div id="breadcrumbs">
			<?php bcn_display(); ?>
		</div>
	<?php } ?>
  
<?php get_footer(); ?>