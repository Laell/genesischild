<?php

/************************************************
* @package genesischild
* @author  NeilGee
* @license GPL-2.0+
* @link    http://wpbeaches.com/
************************************************/

//Menu Plugin - This is what I am using for my mobile menu - https://wordpress.org/plugins/slicknav-mobile-menu/

// Start the engine the other way around - set up child after parent - add in theme supports, actions and filters
add_action( 'genesis_setup', 'genesischild_theme_setup' );

function genesischild_theme_setup() { 
	//Load in theme supports	
	add_theme_support( 'html5' );
	add_theme_support( 'genesis-responsive-viewport' );
	add_theme_support( 'genesis-footer-widgets', 3 );
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header', array(
		'flex-width'      => true,
		'width'           => 400,
		'flex-height'     => true,
		'height'          => 150,
		'header-selector' => '.header-image .site-title > a',
		'header-text'     => false,
	) );
	add_theme_support( 'genesis-after-entry-widget-area' );
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'menu-secondary', 'footer-widgets', 'footer' ) );

	//If using WooCommerce uncomment the theme support below
	//add_theme_support( 'genesis-connect-woocommerce' ); //Uncomment if using woocommerce
	
	
	//Load and order scripts in the head
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );//Remove order of main style sheet
	add_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 998 ); //Order main style sheet 2nd last
	add_action( 'wp_enqueue_scripts', 'genesischild_ie_styles', 999 );	//IE conditional styles load last
	add_action( 'wp_enqueue_scripts', 'genesischild_scripts_styles', 997 ); //All the rest load before
	add_action( 'wp_enqueue_scripts', 'backstretch_background_scripts' );

	//Register extra widget areas
	add_action( 'widgets_init', 'genesischild_extra_widgets' );	
	add_action( 'genesis_before_loop','genesischild_before_entry_widget' );
	add_action( 'genesis_before_footer','genesischild_footerwidgetheader', 5 );
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer','genesischild_footer_widget' );
	add_action( 'genesis_after_footer','genesischild_postfooter_widget' );		
	add_action( 'genesis_before_header','genesischild_preheader_widget' );
	add_action( 'genesis_after_header','genesischild_optin_widget', 9 );


	//Re-arrange header nav
	remove_action( 'genesis_after_header','genesis_do_nav' );
	add_action( 'genesis_header_right','genesis_do_nav' );

	//Allow shortcode in widgets
	add_filter( 'widget_text', 'do_shortcode' );	

	//Allow PHP in widgets
	add_filter( 'widget_text','genesis_execute_php_widgets' );

	//Change the excerpt reqd more	
	add_filter( 'excerpt_more', 'genesischild_read_more_link' );

	//Remove comment HTML tags
	add_filter( 'comment_form_defaults', 'genesischild_comment_form_defaults' );
	add_filter( 'comment_form_defaults', 'genesischild_remove_comment_form_allowed_tags' );

	//Post info change
	add_filter( 'genesis_post_info', 'genesischild_post_info' );

	//Remove Genesis blog page
	add_filter( 'theme_page_templates', 'genesis_remove_blog_archive' );


	//Uncomment and unregister widget areas in function below
	//add_action( 'widgets_init', 'wpb_remove_some_widgets' );

	load_theme_textdomain('genesischild', get_stylesheet_directory_uri() . '/languages');

}



//Remove Unwanted Widgts
function wpb_remove_some_widgets(){
	//Example below, to action these uncomment the add_action above
	unregister_sidebar( 'header-right' );	
}


//Child Theme Functions Go Here

//Script-tac-ulous -> All the Scripts and Styles Enqueued, scripts first - then styles
function genesischild_scripts_styles() {
	wp_enqueue_script ( 'placeholder' , get_stylesheet_directory_uri() . '/js/placeholder.js', array( 'jquery' ), '1', true );
	wp_enqueue_style ( 'googlefonts' , '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,600,700,300,800', '', '2', 'all' );
	wp_enqueue_style ( 'fontawesome' , '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', '' , '4.3.0', 'all' );
	//wp_enqueue_style( 'dashicons' ); //Uncomment if DashIcons required in front end
}

//IE Conditional Styles - gotta load last
function genesischild_ie_styles() {
	wp_register_style( 'ie8', get_stylesheet_directory_uri() . '/css/ie8.css' );//target IE8 and Lower
	$GLOBALS['wp_styles']->add_data( 'ie8', 'conditional', 'lte IE 8' );
	wp_register_style( 'ieall', get_stylesheet_directory_uri() . '/css/ieall.css' );//target IE9 and lower
	$GLOBALS['wp_styles']->add_data( 'ieall', 'conditional', 'IE' );

	wp_enqueue_style( 'ie8' );
	wp_enqueue_style( 'ieall' );
}



//Backstretch for Custom Background Image
 function backstretch_background_scripts() {
	//* Load scripts only if custom background is being used
	if ( ! get_background_image() )
		return;

	wp_enqueue_script( 'backstretch', get_stylesheet_directory_uri() . '/js/backstretch.min.js', array( 'jquery' ), '2.0.4', true );
	wp_enqueue_script( 'backstretch-image', get_stylesheet_directory_uri().'/js/backstretch-initialise.js' , array( 'jquery', 'backstretch' ), '1', true );
	wp_localize_script( 'backstretch-image', 'BackStretchImage', array( 'src' => get_background_image() ) );
}


//Register new Widget areas
function genesischild_extra_widgets() {	
	genesis_register_sidebar( array(
	'id'          => 'preheaderleft',
	'name'        => __( 'PreHeaderLeft', 'genesischild' ),
	'description' => __( 'This is the preheader left area', 'genesischild' ),
	'before_widget' => '<div class="first one-half preheaderleft">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'preheaderright',
	'name'        => __( 'PreHeaderRight', 'genesischild' ),
	'description' => __( 'This is the preheader right area', 'genesischild' ),
	'before_widget' => '<div class="one-half preheaderright">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'hero',
	'name'        => __( 'Hero Home Page', 'genesischild' ),
	'description' => __( 'This is the Hero Home Page area', 'genesischild' ),
	'before_widget' => '<div class="wrap hero">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'optin',
	'name'        => __( 'Optin', 'genesischild' ),
	'description' => __( 'This is the optin area', 'genesischild' ),
	'before_widget' => '<div class="wrap optin">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'home-left',
	'name'        => __( 'Home Left', 'genesischild' ),
	'description' => __( 'This is the home left area', 'genesischild' ),
	'before_widget' => '<div class="widget">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home Middle', 'genesischild' ),
	'description' => __( 'This is the home middle area', 'genesischild' ),
	'before_widget' => '<div class="widget">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'home-right',
	'name'        => __( 'Home Right', 'genesischild' ),
	'description' => __( 'This is the home right area', 'genesischild' ),
	'before_widget' => '<div class="widget">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'before-entry',
	'name'        => __( 'Before Entry', 'genesischild' ),
	'description' => __( 'This is the before content area', 'genesischild' ),
	'before_widget' => '<div class="before-entry">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id' => 'footerwidgetheader',
	'name' => __( 'Footer Widget Header', 'genesischild' ),
	'description' => __( 'This is for the Footer Widget Headline', 'genesischild' ),
	'before_widget' => '<div class="footerwidgetheader">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'footercontent',
	'name'        => __( 'Footer', 'genesischild' ),
	'description' => __( 'This is the general footer area', 'genesischild' ),
	'before_widget' => '<div class="footercontent">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'postfooterleft',
	'name'        => __( 'Post Footer Left', 'genesischild' ),
	'description' => __( 'This is the post footer left area', 'genesischild' ),
	'before_widget' => '<div class="first one-half postfooterleft">',
	'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'postfooterright',
	'name'        => __( 'Post Footer Right', 'genesischild' ),
	'description' => __( 'This is the post footer right area', 'genesischild' ),
	'before_widget' => '<div class="one-half postfooterright">',
	'after_widget' => '</div>',
	) );
}

//Position the PreHeader Area
function genesischild_preheader_widget() {
	echo '<section class="preheadercontainer"><div class="wrap">';
	genesis_widget_area ( 'preheaderleft' );
	genesis_widget_area ( 'preheaderright' );
	echo '</div></section>';
}

//Position the Hero Area
function genesischild_hero_widget() {
	genesis_widget_area ( 'hero', array(
	'before' => '<section class="herocontainer">',
	'after' => '</section>',));
}

//Position the Optin Area
function genesischild_optin_widget() {
	genesis_widget_area ( 'optin', array(
	'before' => '<aside class="optincontainer">',
	'after' => '</aside>',));
}

//Position the Home Area
function genesischild_homecontent_widget() {
	echo '<section class="home-module-container"><div class="wrap">';
	genesis_widget_area ( 'home-left', array(
	'before' => '<div class="first one-third homeleft">',
	'after' => '</div>',));
	genesis_widget_area ( 'home-middle', array(
	'before' => '<div class="one-third homemiddle">',
	'after' => '</div>',));
	genesis_widget_area ( 'home-right', array(
	'before' => '<div class="one-third homeright">',
	'after' => '</div>',));
	echo '</div></section>';
}

//Position Footer Widget Header
function genesischild_footerwidgetheader()  {
	echo '<div class="footerwidgetheader-container"><div class="wrap">';
	genesis_widget_area ( 'footerwidgetheader' );
	echo '</div></div>'; 
}
	
//Position the Footer Area
function genesischild_footer_widget() {
	genesis_widget_area ( 'footercontent', array(
	'before' => '<div class="footercontainer">',
	'after' => '</div>',));
}

//Position the PostFooter Area
function genesischild_postfooter_widget() {
	echo '<div class="postfootercontainer"><div class="wrap">';
	genesis_widget_area ( 'postfooterleft' );
	genesis_widget_area ( 'postfooterright' );
	echo '</div></div>';
}

//Position the Before Content Area
function genesischild_before_entry_widget() {
	if( is_single() ) {
		genesis_widget_area ( 'before-entry' );
	}
}
	

// Remove Genesis Blog & Archive
function genesis_remove_blog_archive( $templates ) {
	unset( $templates['page_blog.php'] );
	unset( $templates['page_archive.php'] );
	return $templates;
}


//Allow PHP to run in Widgets
function genesis_execute_php_widgets( $html ) {
	if ( strpos( $html, "<" . "?php" ) !==false ) {
	ob_start();
	eval( "?".">".$html );
	$html=ob_get_contents();
	ob_end_clean();
		}
	return $html;
}

//Read More Button For Excerpt
function genesischild_read_more_link() {
	return '... <a href="' . get_permalink() . '" class="more-link" title="Read More">Read More</a>';
}

//Remove Author Name on Post Meta
function genesischild_post_info( $post_info ) {
	if ( !is_page() ) {
	$post_info = 'Posted on [post_date] [post_comments] [post_edit]';
	return $post_info;
	}
}

//Change the comments header
function genesischild_comment_form_defaults( $defaults ) {
	$defaults['title_reply'] = __( 'Leave a Comment', 'genesischild' );
	return $defaults;
}

//Remove comment form HTML tags and attributes
function genesischild_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}