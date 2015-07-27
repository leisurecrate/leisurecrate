<?php
/**
 * portal functions and definitions
 *
 * @package portal
 * @since portal 1.0
 * @license GPL 2.0
 */

define( 'SITEORIGIN_THEME_VERSION' , '1.0.5' );
define('SITEORIGIN_THEME_UPDATE_ID', 278);

include get_template_directory() . '/extras/settings/settings.php';

include get_template_directory() . '/extras/adminbar/adminbar.php';
include get_template_directory() . '/extras/plugin-activation/plugin-activation.php';

// Load the theme specific files
include get_template_directory() . '/inc/panels.php';
include get_template_directory() . '/inc/settings.php';
include get_template_directory() . '/inc/extras.php';
include get_template_directory() . '/inc/template-tags.php';
include get_template_directory() . '/inc/gallery.php';
include get_template_directory() . '/inc/widgets.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since portal 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 680; /* pixels */

if ( ! function_exists( 'portal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since portal 1.0
 */
function portal_setup() {
	// Initialize SiteOrigin settings
	siteorigin_settings_init();
	
	// Make the theme translatable
	load_theme_textdomain( 'portal', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add support for custom backgrounds.
	add_theme_support( 'custom-background' , array(
		'default-color' => 'E0E0E0',
		'default-image' => get_template_directory_uri().'/images/patterns/background.png'
	));

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'portal' ),
	) );

	// Enable support for Post Formats
	add_theme_support( 'structured-post-formats', array(
		'video'
	) );

	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery'
	) );

	add_theme_support('siteorigin-premium-teaser', array(
		'page-templates' => true,
	));

	add_theme_support('siteorigin-mobilenav');

	add_theme_support('siteorigin-panels', array(
		'home-page' => true,
	));

	add_theme_support('siteorigin-slider', array(
		'gallery' => 'slider'
	));

	add_image_size('portal-slide', 960, 480, true);

	add_editor_style();

	set_post_thumbnail_size(1050, 382, true);

	if(!is_dir(WP_PLUGIN_DIR.'/siteorigin-panels')){
		// Only include panels lite if the panels plugin doesn't exist
		include get_template_directory() . '/extras/panels-lite/panels-lite.php';
	}
}
endif; // portal_setup
add_action( 'after_setup_theme', 'portal_setup' );

/**
 * Add the sidebars for Portal.
 */
function portal_sidebars_init(){
	register_sidebar( array(
		'name' => __( 'Footer', 'portal' ),
		'id' => 'sidebar-footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action('widgets_init', 'portal_sidebars_init');

/**
 * Setup the WordPress core custom background feature.
 * 
 * @since portal 1.0
 */
function portal_register_custom_background() {
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'portal_custom_background_args', $args );
	add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'portal_register_custom_background' );

/**
 * Register all the bundled scripts
 */
function portal_register_scripts(){
	wp_register_script( 'portal-flexslider' , get_template_directory_uri().'/js/jquery.flexslider.js' , array('jquery'), '2.1' );
	wp_register_script( 'portal-fitvids' , get_template_directory_uri().'/js/jquery.fitvids.js' , array('jquery'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'portal_register_scripts' , 5);

/**
 * Enqueue scripts and styles
 */
function portal_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), SITEORIGIN_THEME_VERSION );

	wp_enqueue_script( 'portal-main' , get_template_directory_uri().'/js/jquery.main.js' , array('jquery', 'portal-flexslider', 'portal-fitvids'), SITEORIGIN_THEME_VERSION );
	wp_localize_script( 'portal-main', 'portalSettings', array(
		'loader' => get_template_directory_uri().'/images/ajax-loader.gif'
	) );
	wp_enqueue_style('portal-google-webfonts', 'http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic|Noto+Serif:400,400italic,700italic', array(), SITEORIGIN_THEME_VERSION);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.min.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'portal_scripts' );

/**
 * Add custom body classes.
 * 
 * @param $classes
 * @package portal
 * @since 1.0
 */
function portal_premium_body_class($classes){
	if(siteorigin_setting('layout_responsive')) $classes[] = 'responsive';
	return $classes;
}
add_filter('body_class', 'portal_premium_body_class');

function portal_wp_head(){
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<!--[if (gte IE 6)&(lte IE 8)]>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/selectivizr.js"></script>
	<![endif]-->
	<?php
}
add_action('wp_head', 'portal_wp_head');

function portal_credits(){
	echo wp_kses_post(siteorigin_setting('text_footer_copyright', sprintf(__("Copyright %s", 'portal'), get_bloginfo('name'))));
	echo apply_filters('siteorigin_attribution_footer', sprintf(__(' - Theme By %s', 'portal'), '<a href="http://siteorigin.com">SiteOrigin</a>') );
}
add_action('portal_credits', 'portal_credits');

function portal_post_class($classes){
	$classes[] = 'entry';
	return $classes;
}
add_filter('post_class', 'portal_post_class');