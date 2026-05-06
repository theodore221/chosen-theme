<?php
/**
 * One-time page setup: ensures pages with the slugs the theme expects
 * (`about`, `privacy`, `safety`) exist in the database, so the matching
 * `templates/page-{slug}.html` templates auto-render at the corresponding URLs.
 *
 * Privacy + Safety are kept as legal/footer-only links (not in primary nav).
 * Earlier IA had Programme / Travel / FAQs / Contact / About pages —
 * those are retired in favour of a single long landing page. Three
 * audience-specific "Get Involved" pages (Partner / Expo / Volunteer)
 * remain because their CTAs and audiences differ from the main scroll.
 *
 * Pages are created with empty post_content because the templates contain
 * all the block markup. Editors can override at any time by editing the
 * page directly in WP Admin — the template still wraps the rendered output.
 *
 * Idempotent: safe to run multiple times. Does not modify pages that
 * already exist (so editors' content edits are preserved on re-activation).
 *
 * @package chosen-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * The pages this theme expects to exist. Slug → display title.
 *
 * @return array<string,string>
 */
function chosen_expected_pages(): array {
	return [
		'partner'   => 'Partner',
		'expo'      => 'Expo',
		'volunteer' => 'Volunteer',
		'privacy'   => 'Privacy Policy',
		'safety'    => 'Child Safety',
	];
}

/**
 * Create any missing pages on theme activation. Idempotent.
 */
function chosen_setup_pages(): void {
	foreach ( chosen_expected_pages() as $slug => $title ) {
		// get_page_by_path() returns null if no page with that slug exists.
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			continue;
		}
		wp_insert_post( [
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => '', // Template provides all content.
			'post_author'  => get_current_user_id() ?: 1,
		], true );
	}
}
add_action( 'after_switch_theme', 'chosen_setup_pages' );

/**
 * Belt-and-braces: also run setup once on init when the theme is updated
 * with new expected pages (e.g. Partner / Expo / Volunteer added later).
 * The original after_switch_theme hook only fires on activation; if the
 * theme was already active when the slug list grew, those pages would
 * never get created. An option flag keeps this to a single DB write.
 */
add_action( 'init', 'chosen_ensure_pages_once' );
function chosen_ensure_pages_once(): void {
	$flag_key = 'chosen_pages_setup_v2';
	if ( get_option( $flag_key ) ) {
		return;
	}
	chosen_setup_pages();
	update_option( $flag_key, '1', false );
}

/**
 * Admin notice: if any expected pages are missing on a request after
 * activation (e.g. one was deleted), surface a "Set up Chosen pages"
 * notice with a one-click recreate action.
 */
function chosen_pages_admin_notice(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$missing = [];
	foreach ( chosen_expected_pages() as $slug => $title ) {
		if ( ! get_page_by_path( $slug, OBJECT, 'page' ) ) {
			$missing[ $slug ] = $title;
		}
	}
	if ( empty( $missing ) ) {
		return;
	}
	$missing_list = implode( ', ', array_map( 'esc_html', $missing ) );
	$action_url = wp_nonce_url(
		admin_url( 'admin-post.php?action=chosen_setup_pages' ),
		'chosen_setup_pages'
	);
	echo '<div class="notice notice-warning"><p><strong>Chosen Theme:</strong> '
		. esc_html__( 'Some expected pages are missing:', 'chosen-theme' )
		. ' ' . $missing_list . '. '
		. '<a href="' . esc_url( $action_url ) . '" class="button button-primary" style="margin-left:8px">'
		. esc_html__( 'Create missing pages', 'chosen-theme' )
		. '</a></p></div>';
}
add_action( 'admin_notices', 'chosen_pages_admin_notice' );

/**
 * Handle the recreate action triggered from the admin notice.
 */
function chosen_handle_setup_pages_action(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'chosen-theme' ), '', [ 'response' => 403 ] );
	}
	check_admin_referer( 'chosen_setup_pages' );
	chosen_setup_pages();
	wp_safe_redirect( admin_url( 'edit.php?post_type=page&chosen_pages_setup=1' ) );
	exit;
}
add_action( 'admin_post_chosen_setup_pages', 'chosen_handle_setup_pages_action' );
