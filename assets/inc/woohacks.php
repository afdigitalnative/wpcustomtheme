<?php

if( function_exists('is_woocommerce') ) {

// Override Woocommerce Quantity Input
function woocommerce_quantity_input() {
    
    global $product;
 
	$defaults = array(
		'input_name'  	=> 'quantity',
		'input_value'  	=> '1',
		'max_value'  	=> apply_filters( 'woocommerce_quantity_input_max', '', $product ),
		'min_value'  	=> apply_filters( 'woocommerce_quantity_input_min', '', $product ),
		'step' 			=> apply_filters( 'woocommerce_quantity_input_step', '1', $product )
	);
	
	if ( ! empty( $defaults['min_value'] ) )
		$min = $defaults['min_value'];
	else $min = 1;
 
	if ( ! empty( $defaults['max_value'] ) )
		$max = $defaults['max_value'];
	else $max = 20;
 
	if ( ! empty( $defaults['step'] ) )
		$step = $defaults['step'];
	else $step = 1;
	
	?>
	
	<li class="quantity col-4">
		<label for="<?php echo esc_attr( $defaults['input_name'] ); ?>">Quantity:</label>
		<input type='button' value='-' class='minus' field="<?php echo esc_attr( $defaults['input_name'] ); ?>" /><input type='text' name="<?php echo esc_attr( $defaults['input_name'] ); ?>" title="<?php echo _x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" value="<?php echo esc_attr( $defaults['input_value'] ); ?>" /><input type='button' value='+' class='plus' field="<?php echo esc_attr( $defaults['input_name'] ); ?>" />
	</li>

		<script type="text/javascript">
	    
	    // This button will increment the value
	    $(".plus").click( function(e) {
	    
	        e.preventDefault();
	        
	        // Define field variable
	        field = "input[name=" + $(this).attr("field") + "]";
	        
	        // Get its current value
	        var currentVal = parseInt($(field).val());
	        
	        // If is not undefined
	        if ( !isNaN(currentVal) && currentVal < <?php echo $max; ?> ) {

	            // Increment
	            $(field).val(currentVal + <?php echo $step; ?> );

	        }
	        
	    });
	    
	    // This button will decrement the value till 0
	    $(".minus").click( function(e) {
	    
	        e.preventDefault();
	        
	        // Define field variable
	        field = "input[name=" + $(this).attr("field") + "]";
	        
	        // Get its current value
	        var currentVal = parseInt($(field).val());
	        
	        // If it is not undefined or its greater than 0
	        if ( !isNaN(currentVal) && currentVal > <?php echo $min; ?> ) {
	            
	            // Decrement one
	            $(field).val(currentVal - <?php echo $step; ?> );
	        
	        }
	        
	    });
	    
	</script>
	<?php
}

add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
/**
 * Remove WooCommerce Generator tag, styles, and scripts from all areas other than store
 * Tested and works with WooCommerce 2.0+
 */
function child_manage_woocommerce_styles() {
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
	if ( is_woocommerce() || is_page('store') || is_shop() || is_product_category() || is_product() || is_cart() || is_checkout() ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_script( 'wc_price_slider' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jquery-placeholder' );
		wp_dequeue_script( 'fancybox' );
		wp_dequeue_script( 'jqueryui' );
		
		wp_deregister_script('wc-add-to-cart-variation');
}}

// Change Breadcrumbs
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
function jk_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => '',
            'wrap_before' => '<div id="breadcrumbs">',
            'wrap_after'  => '</div>',
            'before'      => '',
            'after'       => ''
        );
}

// Custom Woocommerce
function woocommerce_custom_breadcrumb(){
    woocommerce_breadcrumb();
}
add_action( 'woo_custom_breadcrumb', 'woocommerce_custom_breadcrumb' );

// Show price in variation select option
function display_price_in_variation_option_name( $term ) {
	
	global $wpdb, $product;

    $result = $wpdb->get_col( "SELECT slug FROM {$wpdb->prefix}terms WHERE name = '$term'" );

    $term_slug = ( !empty( $result ) ) ? $result[0] : $term;

    $query = "SELECT postmeta.post_id AS product_id
              FROM {$wpdb->prefix}postmeta AS postmeta
			  LEFT JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
			  WHERE postmeta.meta_key LIKE 'attribute_%'
			  AND postmeta.meta_value = '$term_slug'
			  AND products.post_parent = $product->id";

    $variation_id = $wpdb->get_col( $query );

    $parent = wp_get_post_parent_id( $variation_id[0] );

    if ( $parent > 0 ) {
        $_product = new WC_Product_Variation( $variation_id[0] );
        return $term . ' (' . woocommerce_price( $_product->get_price() ) . ')';
    }
    
    return $term;

}
add_filter( 'woocommerce_variation_option_name', 'display_price_in_variation_option_name' );

}