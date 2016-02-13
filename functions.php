<?php

/************************************************
* @package genesischild
* @author  NeilGee
* @license GPL-2.0+
* @link    http://wpbeaches.com/
************************************************/


add_action( 'genesis_setup', 'genesischild_theme_setup', 15 );

/**
 * Genesischild theme set up
 *
 * Start the engine the other way around - set up child after parent - add in theme supports, actions and filters
 *
 *
 * @since 1.0.0
 *
 */
function genesischild_theme_setup() {
	//Load in required files
	//
	//Widget areas registered and positioned
	require_once  get_stylesheet_directory() . '/includes/widgets.php';
	//All scripts and styles to be registered and enqueued
	require_once  get_stylesheet_directory() . '/includes/scripts-styles.php';

	//Allow the theme to be translated
	load_theme_textdomain('genesischild', get_stylesheet_directory_uri() . '/languages');

	//Load in our theme supports
	//
	//HTML5 goodness
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'  ) );
	//RWD viewport
	add_theme_support( 'genesis-responsive-viewport' );
	//Footer Widgets - change number to suit
	add_theme_support( 'genesis-footer-widgets', 3 );
	//Allow for a custom background
	add_theme_support( 'custom-background' );
	//Add support for custom header change the dimensions to suit
	add_theme_support( 'custom-header', array(
		'flex-width'      => true,
		'flex-height'     => true,
		'width'           => 400,
		'height'          => 150,
		'header-text'     => false,
	) );
	//Add Accessibility support
	add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links' ) );
	//Add widget area after posts
	add_theme_support( 'genesis-after-entry-widget-area' );
	//Add structural wraps
	add_theme_support( 'genesis-structural-wraps', array( 'site-inner', 'header', 'menu-secondary', 'footer-widgets', 'footer' ) );

	//Image sizes - add in required image sizes here
	//
	//add_image_size( 'blog-feature', 380, 380, true );


	//Unregister Genesis page layouts
	//
	//genesis_unregister_layout( 'content-sidebar' );
	//genesis_unregister_layout( 'sidebar-content' );
	//genesis_unregister_layout( 'content-sidebar-sidebar' );
	//genesis_unregister_layout( 'sidebar-sidebar-content' );
	//genesis_unregister_layout( 'sidebar-content-sidebar' );
	//genesis_unregister_layout( 'full-width-content' );


	//Declare WooCommerce support for your theme - install the plugin and below and uncomment the line below that
	//https://wordpress.org/plugins/genesis-connect-woocommerce/
	//
	//add_theme_support( 'genesis-connect-woocommerce' );


	//Re-arrange header nav
	remove_action( 'genesis_after_header','genesis_do_nav' );
	add_action( 'genesis_header_right','genesis_do_nav' );


	//Change Read More Button For Excerpt
	function genesischild_read_more_link() {
		return '  <a href="' . get_permalink() . '" class="more-link" title="Read More">Read More</a>';
	}
	add_filter( 'excerpt_more', 'genesischild_read_more_link' );


	//Customize the content limit more markup
	function genesischild_content_limit_read_more_markup( $output, $content, $link ) {

		$output = sprintf( '<p>%s &#x02026;</p><p class="more-link-wrap">%s</p>', $content, str_replace( '&#x02026;', '', $link ) );

		return $output;
	}
	add_filter( 'get_the_content_limit', 'genesischild_content_limit_read_more_markup', 10, 3 );



	//Change the comments reply text
	function genesischild_comment_form_defaults( $defaults ) {
		$defaults['title_reply'] = __( 'Leave a Comment', 'genesischild' );
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
	add_filter( 'comment_form_defaults', 'genesischild_comment_form_defaults' );



	//Remove Author Name on Post Meta
	function genesischild_post_info( $post_info ) {
		if ( !is_page() ) {
		$post_info = 'Posted on [post_date] [post_comments] [post_edit]';
		return $post_info;
		}
	}
	add_filter( 'genesis_post_info', 'genesischild_post_info' );



	// Remove Genesis Blog & Archive
	function genesischild_remove_blog_archive( $templates ) {
		unset( $templates['page_blog.php'] );
		unset( $templates['page_archive.php'] );
		return $templates;
	}
	add_filter( 'theme_page_templates', 'genesischild_remove_blog_archive' );



	//Remove blog header from blog posts page
	remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );



	//Allow SVG Images Via Media Uploader
	function genesischild_add_svg_images($mimetypes) {
		$mimetypes['svg'] = 'image/svg+xml';
		return $mimetypes;
	}
	add_filter('upload_mimes', 'genesischild_add_svg_images');



	//Remove Genesis header style so we can use the customiser and header function below genesischild_swap_header
	remove_action( 'wp_head', 'genesis_custom_header_style');


	//Add an image tag inline in the site title element for the main logo
	function genesischild_swap_header($title, $inside, $wrap) {
	//* Set what goes inside the wrapping tags
		if ( get_header_image() ) :
	$logo = '<img  src="' . get_header_image() . '" width="' . esc_attr( get_custom_header()->width ) . '" height="' . esc_attr( get_custom_header()->height ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
		else:
	$logo =  get_bloginfo('name');
		endif;
	 $inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );
	 //* Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug
	 $wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	 //* A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug
	 $wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
	 //* And finally, $wrap in h1 if HTML5 & semantic headings enabled
	 $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;
	 return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );

	}
	add_filter( 'genesis_seo_title','genesischild_swap_header', 10, 3 );



	//Allow shortcode to run in widgets
	add_filter( 'widget_text', 'do_shortcode' );



	//Allow PHP code to run in Widgets
	function genesischild_execute_php_widgets( $html ) {
		if ( strpos( $html, "<" . "?php" ) !==false ) {
		ob_start();
		eval( "?".">".$html );
		$html=ob_get_contents();
		ob_end_clean();
			}
		return $html;
	}
	add_filter( 'widget_text','genesischild_execute_php_widgets' );



}//<~Closing brace for genesis_setup function

//Mobile Menu - This is what I am using for my mobile menu - https://wordpress.org/plugins/slicknav-mobile-menu/
