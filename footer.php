</div>

<footer>
	<section class="group">
	
		<div id="about" class="col-4">
			<a href="<?php echo get_option('home'); ?>/" class="logo"></a>
			<p>
				Revival Christian Fellowship exists to help people grow in the image of Jesus Christ, through the threefold process of Salvation, Sanctification, and Service, to the glory of God.
			</p>
			<small>
				Service Times: Sun - 8AM, 9:45AM, 11:30AM, & 6PM | Wed - 7PM
			</small>
		</div>
		
		<div class="col-1"></div>
	
		<div class="col-2 revival">
			<?php
			$location = 'footer_left';
			$menu_obj = get_menu_name($location ); 
			wp_nav_menu( array('theme_location' => $location, 'container' => false, 'items_wrap'=> '<h4>'.esc_html($menu_obj->name).'</h4><ul id="%1$s" class="%2$s">%3$s</ul>') );
			?>
		</div>
		
		<div class="col-2 ministries">
			<?php
			$location = 'footer_right';
			$menu_obj = get_menu_name($location ); 
			wp_nav_menu( array('theme_location' => $location, 'container' => false, 'items_wrap'=> '<h4>'.esc_html($menu_obj->name).'</h4><ul id="%1$s" class="%2$s">%3$s</ul>') );
			?>
		</div>
		
		<div class="col-3 connected">
		<h4><span>Get Connected</span></h4>
		<?php echo do_shortcode("[MCform]"); ?>
		<small>Receive weekly updates from Revival.</small>
		</div>
		
		<small class="col-12"><a href="https://mail.revival.tv">webmail</a> | &copy; <?php bloginfo('name'); ?>, <?=date('Y');?>. All Rights Reserved</small>
	</section>
</footer>

<?php wp_footer(); ?>
    
</body>

</html>