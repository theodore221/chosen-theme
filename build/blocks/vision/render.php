<?php
/**
 * chosen/vision block — server-side render.
 *
 * Eyebrow → Anton headline → optional supporting body → 48px gold rule →
 * italic light scripture → gold cite. Scroll-fade entrance via the shared
 * `.chosen-fade-up` IO observer (registered once globally — see end of
 * parts/footer.html / src/css/input.css).
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow   = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline  = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$body      = isset( $attributes['body'] ) ? (string) $attributes['body'] : '';
$scripture = isset( $attributes['scripture'] ) ? (string) $attributes['scripture'] : '';
$cite      = isset( $attributes['cite'] ) ? (string) $attributes['cite'] : '';

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-vision bg-chosen-navy text-white py-20 md:py-28',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="chosen-fade-up mx-auto max-w-content px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $headline ) : ?>
			<h2 class="mt-5 font-display text-[clamp(2.5rem,6vw,4.5rem)] leading-[1.05] uppercase">
				<?php echo esc_html( $headline ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $body ) : ?>
			<p class="mt-6 max-w-xl text-[17px] leading-relaxed text-white/85">
				<?php echo esc_html( $body ); ?>
			</p>
		<?php endif; ?>

		<div class="mt-8 h-[3px] w-12 bg-chosen-gold" aria-hidden="true"></div>

		<?php if ( $scripture ) : ?>
			<blockquote class="mt-6 max-w-xl text-[22px] font-light italic leading-relaxed text-white">
				<?php echo esc_html( $scripture ); ?>
			</blockquote>
		<?php endif; ?>

		<?php if ( $cite ) : ?>
			<cite class="mt-3 inline-block not-italic text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $cite ); ?>
			</cite>
		<?php endif; ?>
	</div>
</section>
<?php /* The IO observer for `.chosen-fade-up` is initialised globally in
   parts/footer.html so multiple blocks share one observer. */ ?>
