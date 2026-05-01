<?php
/**
 * chosen/marquee block — server-side render.
 *
 * A single moving scripture banner. Pure-CSS infinite scroll: items are
 * duplicated inline in markup so the track can translate -50% and loop
 * seamlessly (half is the original sequence, half is the duplicate). The
 * animation pauses on hover and is killed by the reduced-motion media
 * query in src/css/input.css.
 *
 * Intent: this is *not* a tagline grab-bag. It is a moving anchor for the
 * theme verse — Psalm 34:5 — set in Anton, with the cite treated as a
 * gold eyebrow break between repeats. One signal, repeated. No noise.
 *
 * Background variants:
 *   - navy (default): white items with gold accents on navy band
 *   - paper: navy items with gold accents on warm-paper band
 *   - royal: white items with gold accents on royal-blue band
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$items = isset( $attributes['items'] ) && is_array( $attributes['items'] )
	? array_values( array_filter( $attributes['items'], static function ( $i ) {
		return is_array( $i ) && isset( $i['text'] ) && '' !== trim( (string) $i['text'] );
	} ) )
	: [];

if ( empty( $items ) ) {
	return;
}

$speed_attr    = isset( $attributes['speed'] ) ? (string) $attributes['speed'] : 'medium';
$speed_seconds = [ 'slow' => 50, 'medium' => 35, 'fast' => 25 ];
$duration      = isset( $speed_seconds[ $speed_attr ] ) ? $speed_seconds[ $speed_attr ] : 35;

$bg_attr = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'navy', 'paper', 'royal', 'cream', 'sage', 'sky', 'sun' ], true )
	? $attributes['background']
	: 'cream';

switch ( $bg_attr ) {
	case 'paper':
		$bg_class     = 'bg-chosen-paper border-chosen-navy/10';
		$default_text = 'text-chosen-navy';
		$default_alt  = 'text-chosen-navy';
		break;
	case 'cream':
		$bg_class     = 'bg-chosen-cream border-chosen-navy/10';
		$default_text = 'text-chosen-navy';
		$default_alt  = 'text-chosen-navy';
		break;
	case 'sage':
		$bg_class     = 'bg-chosen-sage border-chosen-navy/15';
		$default_text = 'text-chosen-navy';
		$default_alt  = 'text-chosen-navy';
		break;
	case 'sky':
		$bg_class     = 'bg-chosen-sky border-chosen-navy/15';
		$default_text = 'text-chosen-navy';
		$default_alt  = 'text-chosen-navy';
		break;
	case 'sun':
		$bg_class     = 'bg-chosen-sun border-chosen-navy/15';
		$default_text = 'text-chosen-navy';
		$default_alt  = 'text-chosen-navy';
		break;
	case 'royal':
		$bg_class     = 'bg-chosen-royal border-white/10';
		$default_text = 'text-white';
		$default_alt  = 'text-white';
		break;
	case 'navy':
	default:
		$bg_class     = 'bg-chosen-navy border-white/10';
		$default_text = 'text-white';
		$default_alt  = 'text-white';
		break;
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-marquee relative py-8 md:py-12 overflow-hidden border-y ' . $bg_class . ' ' . $default_text,
] );

$render_items = function ( $items, $aria_hidden = false ) use ( $default_alt ) {
	foreach ( $items as $item ) {
		$is_gold = isset( $item['color'] ) && 'gold' === $item['color'];
		$colour  = $is_gold ? 'text-chosen-gold' : $default_alt;
		// Cite-style items render smaller in eyebrow caps; main text is full display.
		$is_cite      = ! empty( $item['cite'] );
		$size_classes = $is_cite
			? 'text-[clamp(14px,1.4vw,18px)] tracking-[0.32em] font-bold'
			: 'text-[clamp(48px,9vw,144px)] tracking-[0.02em] leading-[0.95]';
		?>
		<span class="chosen-marquee__item font-display <?php echo esc_attr( $size_classes ); ?> uppercase <?php echo esc_attr( $colour ); ?>"<?php echo $aria_hidden ? ' aria-hidden="true"' : ''; ?>>
			<?php echo esc_html( $item['text'] ); ?>
		</span>
		<span class="chosen-marquee__sep text-chosen-gold/60"<?php echo $aria_hidden ? ' aria-hidden="true"' : ''; ?>>
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
				<circle cx="7" cy="7" r="2.5" fill="currentColor" />
			</svg>
		</span>
		<?php
	}
};
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Theme verse — Psalm 34:5', 'chosen-theme' ); ?>">
	<div class="chosen-marquee__viewport relative">
		<div class="chosen-marquee__track flex items-center gap-10 md:gap-16 whitespace-nowrap" style="--chosen-marquee-duration: <?php echo (int) $duration; ?>s;">
			<?php $render_items( $items ); ?>
			<?php $render_items( $items, true ); ?>
		</div>
	</div>
</section>
