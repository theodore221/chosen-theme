<?php
/**
 * chosen/quote block — server-side render.
 *
 * Standalone scripture pull-quote. Eyebrow (gold ALL CAPS) → italic light
 * verse (Work Sans 300) → 48px gold rule → gold cite. Reuses the shared
 * .chosen-fade-up scroll-fade entrance defined in src/css/input.css.
 *
 * Background toggle: navy (dark) or warm paper (light). Text colours and
 * verse colour switch accordingly; gold accents are constant.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$verse   = isset( $attributes['verse'] ) ? (string) $attributes['verse'] : '';
$cite    = isset( $attributes['cite'] ) ? (string) $attributes['cite'] : '';
$bg      = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'navy', 'paper' ], true )
	? $attributes['background']
	: 'navy';

if ( '' === trim( $verse ) ) {
	return;
}

$is_paper = 'paper' === $bg;
$bg_class = $is_paper ? 'bg-chosen-paper' : 'bg-chosen-navy';
$verse_class = $is_paper ? 'text-chosen-navy' : 'text-white';

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-quote ' . $bg_class . ' py-20 md:py-28',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="chosen-fade-up mx-auto max-w-content px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<blockquote class="mt-5 text-[clamp(1.5rem,3.5vw,2rem)] font-light italic leading-relaxed <?php echo esc_attr( $verse_class ); ?>">
			<?php echo esc_html( $verse ); ?>
		</blockquote>

		<div class="mt-6 h-[3px] w-12 bg-chosen-gold" aria-hidden="true"></div>

		<?php if ( $cite ) : ?>
			<cite class="mt-3 inline-block not-italic text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $cite ); ?>
			</cite>
		<?php endif; ?>
	</div>
</section>
