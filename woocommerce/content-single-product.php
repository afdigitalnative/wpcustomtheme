<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class('single-product'); ?>>

	<div class="col-6">
	<?php
		/**
		 * woocommerce_show_product_images hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		//do_action( 'woocommerce_before_single_product_summary' );
		echo woocommerce_show_product_images();
	?>
	</div>

	<div class="col-6">

		<?php woocommerce_template_single_title(); ?>
			
		<?php woocommerce_template_single_price(); ?>
		
		<div class="description">	
			<?php the_content(); ?>
		</div>
		
		<?php woocommerce_template_single_add_to_cart(); ?>
		
	</div>

</div>

<!--
<div>
	<h2 class="col-12">Recent Products</h2>
	<?php echo do_shortcode('[recent_products per_page="4" columns="4"]'); ?>
</div>
-->

<?php do_action('woo_custom_breadcrumb'); ?>

<?php do_action( 'woocommerce_after_single_product' ); ?>