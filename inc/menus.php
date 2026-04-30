<?php
defined( 'ABSPATH' ) || exit;

add_action( 'init', 'chosen_register_menus' );
function chosen_register_menus(): void {
	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'chosen-theme' ),
		'footer'  => __( 'Footer Navigation', 'chosen-theme' ),
	] );
}
