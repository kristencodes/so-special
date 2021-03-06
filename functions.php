<?php
/**
 * McClelland Insurance functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package McClelland Insurance
 */

if ( ! function_exists( 'mcclellandinsurance_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mcclellandinsurance_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on McClelland Insurance, use a find and replace
	 * to change 'mcclellandinsurance' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'mcclellandinsurance', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'services' => esc_html__( 'Services Menu', 'mcclellandinsurance' ),
		'personal' => esc_html__( 'Personal Menu', 'mcclellandinsurance'),
		'commercial' => esc_html__( 'Commercial Menu', 'mcclellandinsurance'),
		'info' => esc_html__( 'Info Menu', 'mcclellandinsurance'),
		'request-a-quote' => esc_html__( 'Request A Quote', 'mcclellandinsurance')
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'mcclellandinsurance_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // mcclellandinsurance_setup
add_action( 'after_setup_theme', 'mcclellandinsurance_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mcclellandinsurance_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mcclellandinsurance_content_width', 640 );
}
add_action( 'after_setup_theme', 'mcclellandinsurance_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mcclellandinsurance_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mcclellandinsurance' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mcclellandinsurance_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mcclellandinsurance_scripts() {
	wp_enqueue_style( 'mcclellandinsurance-style', get_stylesheet_uri() );

	wp_enqueue_script( 'mcclellandinsurance-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'mcclellandinsurance-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_deregister_script( 'jquery' );
	$jquery_cdn = "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js";
	wp_enqueue_script( 'jquery', $jquery_cdn, array(), '20130115', true );

	$google_maps = "https://maps.googleapis.com/maps/api/js";
	wp_enqueue_script( 'maps', $google_maps, array('jquery'), true);

	wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery', 'maps'));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mcclellandinsurance_scripts' );

add_filter( 'wp_nav_menu_objects', 'submenu_limit', 10, 2 );

function submenu_limit( $items, $args ) {

    if ( empty($args->submenu) )
        return $items;

    $array_popper = wp_filter_object_list( $items, array( 'title' => $args->submenu ), 'and', 'ID' );
    $parent_id = array_pop( $array_popper );
    $children  = submenu_get_children_ids( $parent_id, $items );

    foreach ( $items as $key => $item ) {

        if ( ! in_array( $item->ID, $children ) )
            unset($items[$key]);
    }

    return $items;
}

function submenu_get_children_ids( $id, $items ) {

    $ids = wp_filter_object_list( $items, array( 'menu_item_parent' => $id ), 'and', 'ID' );

    foreach ( $ids as $id ) {

        $ids = array_merge( $ids, submenu_get_children_ids( $id, $items ) );
    }

    return $ids;
}

function getMcClellandPageName($post, $isMainPage) {
	if(empty($post->post_parent)) {
		return $isMainPage ? get_the_title($post->ID) : "<span class='pageParents'>" . get_the_title($post->ID);
	} else {
		$theTitle = $isMainPage ? "</span>" . get_the_title($post->ID) : get_the_title($post->ID);
		return getMcClellandPageName(get_post($post->post_parent), false) . "<span>></span>" . $theTitle;
	}
}

function getMcClellandPageParentName($post, $asLowerCase = false) {
	if(empty($post->post_parent)) {
		$title = get_the_title($post->ID);
		return $asLowerCase ? str_replace(" ", "-", strtolower($title)) : $title;
	} else {
		return getMcClellandPageParentName(get_post($post->post_parent), $asLowerCase);
	}
}

function getMcClellandMenuName($post, $asLowerCase = true) {
	if(empty($post->post_parent)) {
		$title = str_replace(" ", "-", get_the_title( $post->ID ));
		if($title == "About-Us") {
			return $asLowerCase ? "info" : "About Us";
		}
		return $asLowerCase ? strtolower($title) : $title;
	} else {
		return getMcClellandMenuName(get_post($post->post_parent), $asLowerCase);
	}
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function query_post_type($query) {
    $post_types = get_post_types();

    if ( is_category() || is_tag()) {

        $post_type = get_query_var('faq');

        if ( $post_type ) {
            $post_type = $post_type;
        } else {
            $post_type = $post_types;
        }

        $query->set('post_type', $post_type);

        return $query;
    }
}

add_filter('pre_get_posts', 'query_post_type');



// filter search

function filter_search($query) {
    if ($query->is_search) {
		$query->set('post_type', array('post', 'faq', 'employee', 'partner', 'page'));
    };
    return $query;
};
add_filter('pre_get_posts', 'filter_search');