<?php
/**
 * chosen/sponsor-strip block — server-side render.
 *
 * Sponsors come from the ACF Repeater field "sponsors" registered in
 * inc/acf-sponsor-fields.php. The block.context['sponsors'] arrives via
 * the ACF block bridge (get_field works inside block render too).
 *
 * Each sponsor: { logo: { url, alt, ... }, name: string, url: string|empty }.
 *
 * Logos render greyscale + 70% opacity at rest, snap to colour + full
 * opacity on hover (filter transition, 250ms). If a URL is provided the
 * logo wraps in <a target="_blank" rel="noopener">; otherwise plain <img>.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Inner content (none).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';

// ACF block bridge: get_field works on the current block context.
$sponsors = function_exists( 'get_field' ) ? get_field( 'sponsors' ) : null;

if ( empty( $sponsors ) || ! is_array( $sponsors ) ) {
	// Visitor fallback: render the section with eyebrow + JY medallion only.
	// Ensures the slot reads as an intentional credit rather than empty space.
	$fallback_attrs = get_block_wrapper_attributes( [
		'class' => 'chosen-sponsor-strip chosen-sponsor-strip--fallback bg-chosen-paper py-12 md:py-16',
	] );
	$mark_url = get_template_directory_uri() . '/assets/img/chosen-mark.png';
	?>
	<section <?php echo $fallback_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Brought to you by Jesus Youth Australia', 'chosen-theme' ); ?>">
		<div class="mx-auto max-w-content px-6 text-center">
			<?php if ( $eyebrow ) : ?>
				<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
					<?php echo esc_html( $eyebrow ); ?>
				</p>
			<?php endif; ?>
			<img src="<?php echo esc_url( $mark_url ); ?>"
				alt="Jesus Youth Australia"
				class="chosen-sponsor-strip__fallback-mark mx-auto mt-6 h-16 w-auto md:h-20"
				width="80" height="80"
				loading="lazy"
				decoding="async" />
		</div>
	</section>
	<?php
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<div class="chosen-sponsor-strip-editor-note bg-chosen-paper pb-8 text-center text-[11px] text-chosen-navy/50">'
			. esc_html__( 'Editor note: add sponsor logos via the "Chosen Sponsors" ACF field group to replace this fallback.', 'chosen-theme' )
			. '</div>';
	}
	return;
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-sponsor-strip bg-chosen-paper py-12 md:py-16',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Conference partners', 'chosen-theme' ); ?>">
	<div class="mx-auto max-w-wide px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-center text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<ul class="mt-10 grid list-none grid-cols-2 items-center gap-8 sm:grid-cols-3 md:grid-cols-4 md:gap-12 lg:grid-cols-6" role="list">
			<?php foreach ( $sponsors as $sponsor ) :
				$logo = isset( $sponsor['logo'] ) && is_array( $sponsor['logo'] ) ? $sponsor['logo'] : [];
				$name = isset( $sponsor['name'] ) ? (string) $sponsor['name'] : '';
				$url  = isset( $sponsor['url'] ) ? (string) $sponsor['url'] : '';

				if ( empty( $logo['url'] ) ) {
					continue;
				}

				$alt    = $name ? $name : ( isset( $logo['alt'] ) ? (string) $logo['alt'] : '' );
				$src    = (string) $logo['url'];
				$width  = isset( $logo['width'] ) ? (int) $logo['width'] : 200;
				$height = isset( $logo['height'] ) ? (int) $logo['height'] : 80;

				$img_html = sprintf(
					'<img class="chosen-sponsor-strip__logo" src="%s" alt="%s" loading="lazy" decoding="async" width="%d" height="%d" />',
					esc_url( $src ),
					esc_attr( $alt ),
					$width,
					$height
				);
			?>
				<li class="chosen-sponsor-strip__cell flex items-center justify-center">
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>"
							target="_blank"
							rel="noopener noreferrer"
							class="chosen-sponsor-strip__link block">
							<?php echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="screen-reader-text"><?php
								/* translators: %s: sponsor name */
								echo esc_html( sprintf( __( 'Visit %s', 'chosen-theme' ), $name ) );
							?></span>
						</a>
					<?php else : ?>
						<?php echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
