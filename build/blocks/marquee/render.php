<?php
/**
 * chosen/marquee block — server-side render.
 *
 * Pure-CSS infinite scroll. Items are duplicated inline in markup to make
 * the loop seamless (track translates -50%, half is the original sequence,
 * half is the duplicate). Animation pauses on hover and is killed by the
 * reduced-motion media query in src/css/input.css.
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
$speed_seconds = [ 'slow' => 60, 'medium' => 40, 'fast' => 25 ];
$duration      = isset( $speed_seconds[ $speed_attr ] ) ? $speed_seconds[ $speed_attr ] : 40;

$bg_attr = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'navy', 'paper', 'royal' ], true )
	? $attributes['background']
	: 'navy';

// Surface + default item colour change with the background. Gold stays gold
// (separator + 'gold' items) because gold reads on all three surfaces.
switch ( $bg_attr ) {
	case 'paper':
		$bg_class      = 'bg-chosen-paper border-chosen-navy/10';
		$default_text  = 'text-chosen-navy';
		$default_alt   = 'text-chosen-navy'; // 'white' in items array maps to navy on paper
		break;
	case 'royal':
		$bg_class      = 'bg-chosen-royal border-white/10';
		$default_text  = 'text-white';
		$default_alt   = 'text-white';
		break;
	case 'navy':
	default:
		$bg_class      = 'bg-chosen-navy border-white/10';
		$default_text  = 'text-white';
		$default_alt   = 'text-white';
		break;
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-marquee py-4 overflow-hidden border-y ' . $bg_class . ' ' . $default_text,
] );

$render_items = function ( $items, $aria_hidden = false ) use ( $default_alt ) {
	foreach ( $items as $item ) {
		// 'gold' items always render gold; 'white' items render the surface-appropriate
		// non-gold colour (white on navy/royal, navy on paper).
		$colour = ( isset( $item['color'] ) && 'gold' === $item['color'] ) ? 'text-chosen-gold' : $default_alt;
		?>
		<span class="chosen-marquee__item font-display text-2xl md:text-3xl uppercase tracking-[0.04em] <?php echo esc_attr( $colour ); ?>"<?php echo $aria_hidden ? ' aria-hidden="true"' : ''; ?>>
			<?php echo esc_html( $item['text'] ); ?>
		</span>
		<span class="chosen-marquee__sep text-chosen-gold"<?php echo $aria_hidden ? ' aria-hidden="true"' : ''; ?>>&middot;</span>
		<?php
	}
};
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Theme phrases', 'chosen-theme' ); ?>">
	<div class="chosen-marquee__viewport">
		<div class="chosen-marquee__track flex items-center gap-12 whitespace-nowrap" style="--chosen-marquee-duration: <?php echo (int) $duration; ?>s;">
			<?php $render_items( $items ); ?>
			<?php $render_items( $items, true ); ?>
		</div>
	</div>
</section>
