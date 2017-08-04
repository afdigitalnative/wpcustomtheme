<?php /* Template Name: Leaders */ ?>
<?php get_header(); ?>
			
	<div class="full-grey title-wrap">
		<h1 class="col-12"><?php the_title(); ?></h1>
	</div>
	
	<h2 class="col-12"> Senior Pastor </h2>
	<?php
	echo '<ul id="archives" class="grid">';
		$users = get_users('role=senior_pastor');
		foreach ($users as $user) {
		?>
			<li>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><img src="<?php echo $user->author_image; ?>" alt="<?php echo $user->display_name; ?>"/></a>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><h3><?php echo $user->display_name; ?></h3></a>
			</li>
		<?php
		}
	echo '</ul>';
	?>
	
	<h2 class="col-12"> Executive Pastors </h2>
	<?php
	echo '<ul id="archives" class="grid">';
		$users = get_users('role=executive_pastor');
		foreach ($users as $user) {
		?>
			<li>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><img src="<?php echo $user->author_image; ?>" alt="<?php echo $user->display_name; ?>"/></a>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><h3><?php echo $user->display_name; ?></h3></a>
			</li>
		<?php
		}
	echo '</ul>';
	?>
	
	<h2 class="col-12"> Associate Pastors </h2>
	<?php
	echo '<ul id="archives" class="grid">';
		$users = get_users('role=associate_pastor');
		foreach ($users as $user) {
		?>
			<li>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><img src="<?php echo $user->author_image; ?>" alt="<?php echo $user->display_name; ?>"/></a>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><h3><?php echo $user->display_name; ?></h3></a>
			</li>
		<?php
		}
	echo '</ul>';
	?>
	
	<h2 class="col-12"> Elders </h2>
	<?php
	echo '<ul id="archives" class="grid">';
		$users = get_users('role=elder');
		foreach ($users as $user) {
		?>
			<li>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><img src="<?php echo $user->author_image; ?>" alt="<?php echo $user->display_name; ?>"/></a>
				<a href="<?php echo get_author_posts_url($user->ID); ?>" title="<?php echo $user->display_name; ?>"><h3><?php echo $user->display_name; ?></h3></a>
			</li>
		<?php
		}
	echo '</ul>';
	?>
	
<?php get_footer(); ?>