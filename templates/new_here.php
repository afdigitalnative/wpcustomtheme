<?php /* Template Name: New Here */ ?>
<?php get_header(); ?>
<?php
$post_meta_data = get_post_custom('post_type=page');
$contact_email = $post_meta_data['contact_email'][0]; 
$address = $post_meta_data['contact_name'][0].' '.$post_meta_data['contact_address'][0].' '.$post_meta_data['contact_city_state_zip'][0];
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<section id="<?php echo strtolower(str_replace(' ','-', get_the_title($post))); ?>">

	<div class="full-dark title-wrap">
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

<?php // TABS BEGIN ?>			
<div id="tabs" class="group">    			
	<ul class="tab-nav group">
        <li><a href="#about" class="tab-4 current">About</a></li>
        <li><a href="#beliefs" class="tab-4">Beliefs</a></li>
        <li><a href="#faq" class="tab-4">FAQ</a></li>
        <li><a href="#location" class="tab-4" onclick="load()">Location</a></li>
    </ul>
	
	<?php // CONTENT BEGIN ?>
	<div class="list-wrap col-12 group">	
		 
		 <?php // ABOUT ?>
		 <section id="about">
		 	<?php the_content(); ?>
		 </section>
		 
		 <?php // BELIEFS ?>
		 <section id="beliefs" class="hide">
			<?php echo apply_filters('the_content', $post_meta_data['newhere_beliefs'][0]); ?>
		 </section>
		
		 <?php // FAQ ?>
		 <section id="faq" class="hide">
		 	<?php echo apply_filters('the_content', $post_meta_data['newhere_faq'][0]); ?>
		 </section>
		 
		 <?php // LOCATION ?>
		 <section id="location" class="hide group">
		 	<div id="contact" class="col-6">
				<h2>Visit Revival</h2>
				
				<div class="col-6">
				<?php if(isset($post_meta_data['contact_name']) && isset($post_meta_data['contact_address']) && isset($post_meta_data['contact_city_state_zip'])) { ?>
					<h3>Location</h3>
					<ul>
					<li><?php echo $post_meta_data['contact_name'][0]; ?></li>
					<li><?php echo $post_meta_data['contact_address'][0]; ?></li>
					<li><?php echo $post_meta_data['contact_city_state_zip'][0]; ?></li>
					</ul>
					<a href="https://maps.google.com/maps?q=<?php echo str_replace(' ','+',$address); ?>" target="_blank">Get Directions &raquo;</a>
				<?php } ?>
				</div>
				
				<div class="col-6">
				<?php if(isset($post_meta_data['contact_phone'][0]) || isset($post_meta_data['contact_fax'][0]) || isset($post_meta_data['contact_email'][0])) { ?>
					<h3>Service Times</h3>
					<ul>
					<li>Sun - 8AM, 9:45AM, 11:30AM, & 6PM</li>
					<li>Wed - 7PM</li>
					</ul>
				<?php } ?>
				</div>
			</div>
			
			<div id="location-map" class="col-6">
				<h2>Map</h2>
				<div id="map"></div>
			</div>
		 </section>
	 </div>
</div>
<?php // TABS END ?>

<?php endwhile; ?>
<?php endif; ?>

<?php if(function_exists('edit_post_link')) { ?>
	<?php echo '<div class="col-12">'; edit_post_link('Edit This Page'); echo '</div>'; ?>
<?php } ?>

</section>

<?php get_footer(); ?>