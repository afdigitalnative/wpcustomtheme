<?php get_header(); $post_meta_data = get_post_custom('post_type=sermons'); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); setPostViews(get_the_ID()); ?>
<?php $sermon_series = get_the_term_list( $post->ID, 'series'); ?>

<?php // MEDIA BEGIN ?>
<div id="sermon" class="full-dark">	
	<h1 class="title">
		<?php the_title(); ?>
	</h1>
	<div id="media-container">
		<div id="media-wrap">
			<?php display_sermon_media(); ?>
		</div>
	</div>
</div>

<?php // TABS BEGIN ?>			
<div id="tabs" class="full-light group">    			
	<ul class="tab-nav group">
		<?php 
		if( isset($post_meta_data['sermon_passage']) || isset($post_meta_data['sermon_start_verse']) || isset($post_meta_data['sermon_end_verse']) || isset($post_meta_data['sermon_text']) ) { $scripture = "Scripture,"; } else { $scripture = ''; }
		
		if(isset($post_meta_data['sermon_notes'])){ $notes = 'Notes,'; } else { $notes = ''; }
		
		if(isset($post_meta_data['sermon_video']) || isset($post_meta_data['sermon_audio']) || isset($post_meta_data['sermon_document']) ) { $downloads = 'Downloads'; } else { $downloads = ''; }
		
		createTabs('Description,'. $scripture . $notes . $downloads);
		?>
    </ul>
	
	<?php // CONTENT BEGIN ?>
	<div class="list-wrap col-9 group">	
		 
		 <?php // DESCRIPTION ?>
		 <section id="description">
		 	<h4 class="tab-title">
		 		<?php if(!isset($post_meta_data['sermon_guest'])) { ?>
		 			<?php the_author_posts_link(); echo ' / '; ?>
		 		<?php } else { ?>
		 			<?php echo $post_meta_data['sermon_guest'][0].' / '; ?>
		 		<?php } the_date(); ?>
		 	</h4>
         	<?php the_content(); ?>
		 </section>
		 
		 <?php // SCRIPTURE ?>
		 <?php if( isset($post_meta_data['sermon_passage']) || isset($post_meta_data['sermon_start_verse']) || isset($post_meta_data['sermon_end_verse']) || isset($post_meta_data['sermon_text']) ) { ?>
		 <section id="scripture" class="hide">
			 <h4 class="tab-title">
			 	<?php echo $post_meta_data['sermon_passage'][0]; ?>
			 	<?php echo $post_meta_data['sermon_start_verse'][0]; ?>
			 	<?php if(get_post_meta($post->ID, 'sermon_end_verse', true)) { 
					echo ' - ' . $post_meta_data['sermon_end_verse'][0]; 
				} ?>
			 </h4>
			 <p>
			 	<?php if ( isset($post_meta_data['sermon_text']) ) echo $post_meta_data['sermon_text'][0]; ?>
			 </p>
		 </section>
		 <?php } ?>
		
		 <?php // NOTES ?>
		 <?php if(isset($post_meta_data['sermon_notes'])) { ?>
		 <section id="notes" class="hide">
		 	<h4 class="tab-title">
		 		Pastor Notes
		 	</h4>
            <p><?php echo $post_meta_data['sermon_notes'][0]; ?></p>
		 </section>
		 <?php } ?>
		 
		 <?php // DOWNLOADS ?>
		 <?php if( isset($post_meta_data['sermon_video']) || isset($post_meta_data['sermon_audio']) || isset($post_meta_data['sermon_document']) ) { ?>
		 <section id="downloads" class="hide">
            <h4 class="tab-title">
		 		Downloads
		 	</h4>
            <ul>
            <?php if(get_post_meta($post->ID, 'sermon_video', true)) { ?>
            	<li><a href="<?php echo $post_meta_data['sermon_video'][0]; ?>">Video</a></li>
            <?php } ?>
            <?php if(get_post_meta($post->ID, 'sermon_audio', true)) { ?>
            	<li><a href="<?php echo $post_meta_data['sermon_audio'][0]; ?>">Audio</a></li>
            <?php } ?>
            <?php if(get_post_meta($post->ID, 'sermon_document', true)) { ?>
            	<li><a href="<?php echo $post_meta_data['sermon_document'][0]; ?>">PDF</a></li>
            <?php } ?>
            </ul>
		 </section>
		 <?php } ?>
		 <?php // TABS END ?>
	</div>
		
	<?php // SIDEBAR ?>
	<div id="sidebar-sermon" class="col-3 group">
		<?php if (function_exists('sharrre')) sharrre(); ?>
		<span class="tab-title">
			Sermons In This Series:
		</span>
		<?php if (function_exists('related_posts')) { related_posts('sermon', 'series', 5, 'View All Sermons In This Series'); }?>
	</div>
</div>

<div class="col-9">
	<?php //comments_template(); ?>
</div>

<?php endwhile; ?>
<?php endif; ?>

<?php echo '<div class="col-12">'; edit_post_link('Edit This Sermon'); echo '</div>'; ?>

<?php if(function_exists('bcn_display')) { ?>
	<?php echo '<div id="breadcrumbs">'; bcn_display(); echo '</div>'; ?>
<?php } ?>

<?php get_footer(); ?>