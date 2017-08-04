<?php /* Template Name: Contact */ ?>
<?php get_header(); ?>
<?php
$post_meta_data = get_post_custom('post_type=page');
$contact_email = $post_meta_data['contact_email'][0]; 
$address = $post_meta_data['contact_name'][0].' '.$post_meta_data['contact_address'][0].' '.$post_meta_data['contact_city_state_zip'][0];
?>
			
	<div class="full-dark title-wrap">
		<?php if ( has_post_thumbnail() ) { ?>
			<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>" class="col-12" />
		<?php } else { ?>
			<h1 class="col-12"><?php the_title(); ?></h1>
		<?php } ?>
	</div>

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
	<?php if(isset($post_meta_data['contact_phone']) || isset($post_meta_data['contact_fax']) || isset($post_meta_data['contact_email'])) { ?>
		<h3>Reach Us At</h3>
		<ul>
		<li><?php echo $post_meta_data['contact_phone'][0]; ?></li>
		<li><?php echo $post_meta_data['contact_fax'][0]; ?></li>
		<li><a href="mailto:<?php echo convertEmail($contact_email); ?>"><?php echo convertEmail($contact_email); ?></a></li>
		</ul>
	<?php } ?>
	</div>
</div>

<div id="contact-form" class="col-6">
	<h2>Message Revival</h2>
	<?php
 	if(isset($post_meta_data['contact_form'])) { 
 		$email = $post_meta_data['contact_form'][0];
 		echo do_shortcode('[contact email="'.$email.'"]');
 	}
 	?>
</div>

<div class="col-12">
	<?php edit_post_link('Edit this page.', '<p>', '</p>'); ?>
</div>
	
<?php get_footer(); ?>