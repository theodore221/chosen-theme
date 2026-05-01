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
$bg      = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'navy', 'paper', 'cream', 'sage', 'sky', 'sun', 'coral' ], true )
	? $attributes['background']
	: 'navy';

if ( '' === trim( $verse ) ) {
	return;
}

$is_navy_bg = 'navy' === $bg;
$bg_class = [
	'navy'  => 'bg-chosen-navy',
	'paper' => 'bg-chosen-paper',
	'cream' => 'bg-chosen-cream',
	'sage'  => 'bg-chosen-sage',
	'sky'   => 'bg-chosen-sky',
	'sun'   => 'bg-chosen-sun',
	'coral' => 'bg-chosen-coral',
][ $bg ];
$verse_class = $is_navy_bg ? 'text-white' : 'text-chosen-navy';

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-quote relative overflow-hidden ' . $bg_class . ' py-28 md:py-40',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<span class="chosen-display-mark absolute top-2 left-2 md:top-6 md:left-8 select-none" aria-hidden="true">&ldquo;</span>
	<div class="chosen-fade-up relative mx-auto max-w-content px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<blockquote class="mt-6 text-[clamp(1.75rem,4.5vw,3rem)] font-light italic leading-[1.25] <?php echo esc_attr( $verse_class ); ?>" data-split="line">
			<?php echo esc_html( $verse ); ?>
		</blockquote>

		<span class="chosen-rule-grow mt-10 w-12 block" aria-hidden="true"></span>

		<?php if ( $cite ) : ?>
			<cite class="mt-4 inline-block not-italic text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $cite ); ?>
			</cite>
		<?php endif; ?>
	</div>
</section>
