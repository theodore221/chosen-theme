<?php
/**
 * chosen/marquee block — server-side render.
 *
 * Pure-CSS infinite scroll. Items are duplicated inline in markup to make
 * the loop seamless (track translates -50%, half is the original sequence,
 * half is the duplicate). Animation pauses on hover and is killed by the
 * reduced-motion media query in src/css/input.css.
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
	return; // Don't render an empty marquee.
}

$speed_attr   = isset( $attributes['speed'] ) ? (string) $attributes['speed'] : 'medium';
$speed_seconds = [
	'slow'   => 60,
	'medium' => 40,
	'fast'   => 25,
];
$duration = isset( $speed_seconds[ $speed_attr ] ) ? $speed_seconds[ $speed_attr ] : 40;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-marquee bg-chosen-navy text-white py-4 overflow-hidden border-y border-white/10',
] );

$render_items = function ( $items, $aria_hidden = false ) {
	foreach ( $items as $item ) {
		$colour = ( isset( $item['color'] ) && 'gold' === $item['color'] ) ? 'text-chosen-gold' : 'text-white';
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
