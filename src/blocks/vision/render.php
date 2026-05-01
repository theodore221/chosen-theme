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
$bg        = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'navy', 'royal', 'paper', 'cream', 'sage', 'sky', 'aqua', 'sun', 'coral' ], true )
	? $attributes['background']
	: 'navy';

// Dark surfaces use white text; light tints use navy text.
$is_dark_bg = in_array( $bg, [ 'navy', 'royal' ], true );
$bg_class = [
	'navy'  => 'bg-chosen-navy text-white',
	'royal' => 'bg-chosen-royal text-white',
	'paper' => 'bg-chosen-paper text-chosen-navy',
	'cream' => 'bg-chosen-cream text-chosen-navy',
	'sage'  => 'bg-chosen-sage text-chosen-navy',
	'sky'   => 'bg-chosen-sky text-chosen-navy',
	'aqua'  => 'bg-chosen-aqua text-chosen-navy',
	'sun'   => 'bg-chosen-sun text-chosen-navy',
	'coral' => 'bg-chosen-coral text-chosen-navy',
][ $bg ];
$head_class       = $is_dark_bg ? 'text-white' : 'text-chosen-navy';
$body_class       = $is_dark_bg ? 'text-white/85' : 'text-chosen-navy/85';
$scripture_class  = $is_dark_bg ? 'text-white' : 'text-chosen-navy';

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-vision ' . $bg_class . ' py-28 md:py-40',
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
				class="chosen-display-2xl mt-6 <?php echo esc_attr( $head_class ); ?>"
				data-split="line"
			>
				<?php echo esc_html( $headline ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $body ) : ?>
			<p class="mt-8 max-w-2xl text-[clamp(17px,1.6vw,22px)] font-light italic leading-relaxed <?php echo esc_attr( $body_class ); ?>">
				<?php echo esc_html( $body ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $scripture || $cite ) : ?>
			<span class="chosen-rule-grow mt-10 w-12 block" aria-hidden="true"></span>

			<?php if ( $scripture ) : ?>
				<blockquote class="mt-6 max-w-xl text-[22px] font-light italic leading-relaxed <?php echo esc_attr( $scripture_class ); ?>">
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
