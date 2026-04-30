<?php
/**
 * Chosen Theme — functions.php
 */

defined( 'ABSPATH' ) || exit;

define( 'CHOSEN_VERSION', '1.0.0' );
define( 'CHOSEN_REGISTER_URL', '' ); // TODO: set to real signup URL before launch

require_once get_template_directory() . '/inc/block-registration.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/security.php';

add_action( 'after_setup_theme', 'chosen_setup' );
function chosen_setup(): void {
	add_theme_support( 'block-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
}

add_action( 'wp_enqueue_scripts', 'chosen_enqueue_assets' );
function chosen_enqueue_assets(): void {
	wp_enqueue_style(
		'chosen-fonts',
		'https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400&family=Anton&family=Bebas+Neue&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'chosen-design-tokens',
		get_template_directory_uri() . '/design-system/colors_and_type.css',
		[ 'chosen-fonts' ],
		CHOSEN_VERSION
	);

	wp_enqueue_style(
		'chosen-main',
		get_template_directory_uri() . '/assets/css/main.css',
		[ 'chosen-design-tokens' ],
		CHOSEN_VERSION
	);
}
