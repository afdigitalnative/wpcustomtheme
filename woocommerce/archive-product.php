<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		
		<div class="full-grey title-wrap">
			<h1 class="col-12">
				<?php woocommerce_page_title(); ?>
			</h1>
		</div>
		
		<?php endif; ?>
		
		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				//do_action( 'woocommerce_before_shop_loop' );
			?>
			
			<div class="col-3 product-sidebar">
				<h2>Sorting</h2>
				<?php echo woocommerce_catalog_ordering(); ?>
				
				<h2>Categories</h2>
				<ul class="product-categories">
				<?php 
				
				$terms = get_terms('product_cat');
				
				$count = count($terms); $i=0;
				
				if ($count > 0) {
				
					$term_list = null;
				
				    foreach ($terms as $term) {
				        $i++;
				        ?>
				        <li>
				    		<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo sprintf(__('View all post filed under %s', 'oe'), $term->name); ?>"><?php echo $term->name; ?></a>
				        </li>
				    	<?php
				    }
				    
				    echo $term_list;
				}
				
				?>
				</ul>
			</div>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

<?php get_footer('shop'); ?>