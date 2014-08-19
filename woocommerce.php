<?php
/**
 * Template Name: WooCommerce Page
*/

get_header();

do_action('st_content_wrap');

$type = get_post_type();

if ($type == 'product') {
	woocommerce_content();
} else {
	get_template_part( '/loops/loop', 'page' );
}

st_content_wrap_close();

if ( is_active_sidebar( 'shop' ) ) :
	st_before_sidebar();
	dynamic_sidebar( 'shop' );
	st_after_sidebar();
endif;

get_footer();

?>