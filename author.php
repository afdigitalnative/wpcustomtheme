<?php get_header();?>
<?php
if(isset($_GET['author_name'])) {
	$author = get_userdatabylogin($author_name);
} else {
	$author = get_userdata(intval($author));
}
$id = $author->ID;
?>

<?php // MEDIA BEGIN ?>
<div class="full-dark notabs">	
	<div id="banner">
		<div id="leader-title">
			<h1>
				<?php echo $author->display_name; ?>
			</h1>
			<em>
				<?php echo str_replace('_', ' ', get_user_role($id)); ?>
			</em>
		</div>
		<?php echo '<img src="'.$author->author_image.'" />'; ?>
	</div>			
</div>

<div class="full-light">
	<div class="about col-9">
		<?php if($author->author_about != '') { ?>
		<h2>About</h2>
			<?php echo $author->author_about; ?>
		<?php } if($author->author_testimony != '') { ?>
		<h2>Testimony</h2>
			<?php echo $author->author_testimony; ?>
		<?php } if($author->author_scripture != '') { ?>
		<h2>Favorite Scripture</h2>
			<?php echo $author->author_scripture; ?>
		<?php } ?>
	</div>
	
	<div class="col-3">	 
		<table id="side-ministry">
			<?php if($author->author_bday != '') { ?>
			<tr>
			  <td>Birthday</td>
			  <td><?php echo $author->author_bday; ?></td>
			</tr>
			<?php } if($author->author_spouse != '') { ?>
			<tr>
			  <td>Spouse</td>
			  <td><?php echo $author->author_spouse; ?></td>
			</tr>
			<?php } if($author->author_married != '') { ?>
			<tr>
			  <td>Married Since</td>
			  <td><?php echo $author->author_married; ?></td>
			</tr>
			<?php } if($author->author_children_num != '') { ?>
			<tr>
			  <td>Children</td>
			  <td><?php echo $author->author_children_num; ?></td>
			</tr>
			<?php } if($author->author_previous != '') { ?>
			<tr>
			  <td>Previous Job</td>
			  <td><?php echo $author->author_previous; ?></td>
			</tr>
			<?php } if($author->author_hobbies != '') { ?>
			<tr>
			  <td>Hobbies</td>
			  <td><?php echo $author->author_hobbies; ?></td>
			</tr>
			<?php } if($author->facebook != '' || $author->twitter != '') { ?>
			<tr>
			  <td>Social</td>
			  <td>
			  	<?php if($author->facebook != '') { ?>
			  		<a href="https://www.facebook.com/<?php echo $post_meta_data['ministry_facebook'][0]; ?>" target="_blank" class="social">F</a>
			      <?php } if($author->twitter != '') { ?>
			  		<a href="https://twitter.com/<?php echo $author->twitter; ?>" target="_blank" class="social">T</a>
			  	<?php } ?>
			  </td>
			</tr>
			<?php } ?>
		</table>
	</div>
	
		<?php 
		query_posts( array( 
			'post_type' => array(
				'sermon'
			),
			'posts_per_page' => 8,
			'author' => $id
			)
		);
		if (have_posts()) { 
		?>
		<h2 class="col-12">Recent Sermons By <?php echo $author->display_name; ?></h2>
		<ul id="archives" class="grid">
			<?php while (have_posts()) : the_post(); ?>
				<li>
					<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="alternate" class="hvr" >
						<?php the_post_thumbnail(); ?>
					</a>
					<h3>
						<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="alternate">
						<?php the_title_limit( 26, '...'); ?>
						</a>
					</h3>
					<div class="excerpt">
						<?php the_excerpt() ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php } else { ?>
			<?php
			query_posts( array(
				'posts_per_page' => 8,
				'author' => $id
				)
			);
			if (have_posts()) { 
			?>
				<h2 class="col-12">Recent Posts By <?php echo $author->display_name; ?></h2>
				<ul id="archives" class="grid">
					<?php while (have_posts()) : the_post(); ?>
						<li>
							<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="alternate" class="hvr" >
								<?php the_post_thumbnail(); ?>
							</a>
							<h3>
								<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="alternate">
								<?php the_title_limit( 26, '...'); ?>
								</a>
							</h3>
							<div class="excerpt">
								<?php the_excerpt() ?>
							</div>
						</li>
					<?php endwhile; wp_reset_postdata(); ?>
				</ul>
			<?php } ?>
		<?php } wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>