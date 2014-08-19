<?php
/**
 * Mobius theme functions and definitions
 *
 * Other functions are attached to action and filter hooks in WordPress to change core functionality.
 *
 * Layout Functions:
 *
 * st_above_header
 * st_header_open
 * st_top_bar
 * st_logo
 * st_header_close
 * st_below_header
 * st_navbar
 * st_before_content
 * st_after_content
 * st_before_comments
 * st_after_comments
 * st_before_footer
 * st_footer
 * st_footernav
 * st_after_footer
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, smpl_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage smpl
 * @since smpl 0.1
 */

// Defines the font stacks displayed in Theme Options
if (! function_exists('st_custom_theme_fonts'))  {

	function st_custom_theme_fonts() {
	$default = array(
		'open_sans' => 'Open Sans',
		'helvetica' => 'Helvetica',
		'arial' => 'Arial',
		'tahoma' => 'Tahoma',
		'georgia' => 'Georgia',
		'cambria' => 'Cambria',
		'palatino' => 'Palatino',
		'droidsans' => 'Droid Sans',
		'droidserif' => 'Droid Serif'
	);
	return $default;
	}
} //endif function_exists
add_filter( 'of_recognized_font_faces', 'st_custom_theme_fonts' );


// Defines the font stacks used in CSS
if (! function_exists('st_custom_font_stacks'))  {

	function st_custom_font_stacks() {
		$default = array(
		'open_sans' => '"Open Sans", sans-serif',
		'helvetica' => '"HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif',
		'arial' => 'Arial, Helvetica, sans-serif',
		'georgia' => 'Constantia, "Lucida Bright", Lucidabright, "Lucida Serif", Lucida, "DejaVu Serif", "Bitstream Vera Serif", "Liberation Serif", Georgia, serif',
		'cambria' => 'Cambria, "Hoefler Text", Utopia, "Liberation Serif", "Nimbus Roman No9 L Regular", Times, "Times New Roman", serif',
		'tahoma' => 'Tahoma, Verdana, Segoe, sans-serif',
		'palatino' => '"Palatino Linotype", Palatino, Baskerville, Georgia, serif'
	);
	return $default;
	}

} //endif function_exists
add_filter( 'st_font_faces', 'st_custom_font_stacks' );

// Load Google Fonts
// Consider adding multiple fonts with the Pipe "|" character for all registered fonts for a single http request.
// Example: family?family=Font+Name1:variants|Font+Name2:variants

function st_load_google_fonts() {

wp_register_style('open_sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,300,600,700,800');
wp_enqueue_style( 'open_sans');

}

add_action('wp_print_styles', 'st_load_google_fonts');


// Adds additional settings to use specific font weights
if (! function_exists('st_font_weights'))  {

	function st_font_weights() {
		$default = array(
			'normal' => 'Normal',
			'italic' => 'Italic',
			'bold' => 'Bold',
			'bold italic' => 'Bold Italic',
			'200' => '200',
			'300' => '300',
			'300italic' => '300italic',
			'400' => '400',
			'400italic' => '400italic',
			'600' => '600',
			'600italic' => '600italic',
			'700' => '700',
			'900' => '900'
		);
		return $default;
	}
} //endif function_exists

add_filter( 'of_recognized_font_styles', 'st_font_weights' );


// Single Post Image - Displays featured thumbnail on single Posts
//
// function single_postimage($content) {
//     global $post;
//     echo get_the_post_thumbnail($post->ID,'large',array('class' => 'aligncenter scale-with-grid'));
// }
// add_filter('st_single_post_image','single_postimage');


// Single Page Image - Displays featured thumbnail on single Pages
//
// function single_pageimage($content) {
//     global $post;
//     echo get_the_post_thumbnail($post->ID,'large',array('class' => 'aligncenter scale-with-grid'));
// }
// add_filter('st_single_page_image','single_pageimage');


/*-----------------------------------------------------------------------------------*/
// WooCommerce Compatibility
/*-----------------------------------------------------------------------------------*/

// Add Theme Support for WC
add_theme_support( 'woocommerce' );

// Wrap WC in native theme functions
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'st_content_wrap', 10);
add_action('woocommerce_after_main_content', 'st_content_wrap_close', 10);



// Disable WC styles

function st_dequeue_woo_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );
	return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'st_dequeue_woo_styles' );



// Add custom theme styles to WC

function st_woocommmerce_styles() {
    wp_register_style('woocommerce', get_bloginfo('stylesheet_directory').'/woocommerce.css', array('style'), $version, 'screen, projection');
    wp_enqueue_style( 'woocommerce');
}
add_filter ('add_stylesheets','st_woocommmerce_styles');



// Adds a unique WooCommerce 'Shop' widget location

function st_woocommerce_sidebar() {
    register_sidebar( array(
        'name' => __( 'Shop', 'smpl' ),
        'id' => 'shop',
        'description' => __( 'WooCommerce Sidebar', 'smpl' ),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
        )
    );
}
add_action( 'init', 'st_woocommerce_sidebar' );


// 3 products per row

function loop_columns() {
	return 3;
}
add_filter('loop_shop_columns', 'loop_columns');

/**
* Optional:
* If you need to change the content ans sidebar widths for any reason, uncomment the functions below:
*/

// Set the column width
// function woocommerce_filter_content_width() {
// 	if ( is_shop() || is_woocommerce() ) {
// 		return 'eleven';
// 	}
// }
// Set the sidebar width
// function woocommerce_filter_sidebar_width() {
// 	if ( is_shop() || is_woocommerce() ) {
// 		return 'five';
// 	}
// }
// Apply the content width
// function set_woocommerce_filter_content_width() {
// 	if ( is_shop() || is_woocommerce() ) {
// 		add_filter('st_filter_content_width', 'woocommerce_filter_content_width');
// 	}
// }
// Apply the sidebar width
// function set_woocommerce_filter_sidebar_width() {
// 	if ( is_shop() || is_woocommerce() ) {
// 		add_filter('st_sidebar_width', 'woocommerce_filter_sidebar_width');
// 	}
// }
// add_action('wp', 'set_woocommerce_filter_content_width');
// add_action('wp', 'set_woocommerce_filter_sidebar_width');

