<aside class="col-3 group">
<span class="tab-title">Recent Posts</span>
<ul id="recent-posts">
<?php
add_filter( 'posts_where', 'post_password_filter' );
$recent_posts = new WP_Query( 'showposts=5' );
while ( $recent_posts -> have_posts() ) : $recent_posts -> the_post();
	?>
	<li>
	<h4 class="recent-title">
		<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
			<?php the_title(); ?>
		</a>
		<span><?php the_time('F d, Y'); ?></span>
	</h4>
	</li>
	<?php 
endwhile;
remove_filter( 'posts_where', 'post_password_filter' );
?>
</ul>

<!--<span class="tab-title">Categories</span>
<ul id="aside-cats">
	<?php wp_list_categories(array('show_count' => 1,'exclude' => '','title_li' => '')); ?>
</ul> -->

<span class="tab-title">Archives</span>
<ul class="aside-year">
	<?php
	$years = $wpdb->get_col(
		"
		SELECT DISTINCT YEAR(post_date)
		FROM $wpdb->posts
		WHERE post_status = 'publish'
			AND post_type = 'post'
			AND post_password = ''
		ORDER BY post_date DESC
		"
	);
	foreach($years as $year) :
	?>
	<li><h4><?php echo $year; ?></h4>
	    <ul class="aside-month">
	        <?    
	        $months = $wpdb->get_col(
		        "
		        SELECT DISTINCT MONTH(post_date)
		        FROM $wpdb->posts
		        WHERE post_status = 'publish'
		        	AND post_type = 'post'
		        	AND YEAR(post_date) = '$year'
		        	AND post_password = ''
		        ORDER BY post_date DESC
		        "
	        );
	        foreach($months as $month) :
	        ?>
	            <li><h5><a href="<?php echo get_month_link($year, $month); ?>">
	                <?php echo date( 'F', mktime(0, 0, 0, $month) );?></a></h5>
		            <ul class="aside-posts">
					<?php            
					global $wpdb;
					$titles = $wpdb->get_col( 
						"
					    SELECT DISTINCT ID
					    FROM $wpdb->posts
					    WHERE post_status = 'publish'
					        AND post_type = 'post'
					        AND YEAR(post_date) =  '$year'
					        AND MONTH(post_date) = '$month'
					        AND post_password = ''
					    ORDER BY post_date DESC
					    
					    "
					);
					
					foreach ($titles as $title) :
						echo '<li><a href="'.get_permalink($title).'">'.get_the_title($title).'</a></li>';
					endforeach;
					?>
		            </ul>
	            </li>
	        <?php endforeach; ?>
	    </ul>
	</li>
	<?php endforeach; wp_reset_postdata(); ?>
</ul>
</aside>