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
	'class' => 'chosen-vision bg-chosen-navy text-white py-28 md:py-40',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="chosen-fade-up mx-auto max-w-wide px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $headline ) : ?>
			<h2
				class="chosen-display-2xl mt-6 text-white"
				data-split="line"
			>
				<?php echo esc_html( $headline ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $body ) : ?>
			<p class="mt-8 max-w-2xl text-[clamp(17px,1.6vw,22px)] font-light italic leading-relaxed text-white/85">
				<?php echo esc_html( $body ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $scripture || $cite ) : ?>
			<span class="chosen-rule-grow mt-10 w-12 block" aria-hidden="true"></span>

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
		<?php endif; ?>
	</div>
</section>
<?php /* The IO observer for `.chosen-fade-up` is initialised globally in
   parts/footer.html so multiple blocks share one observer. */ ?>
