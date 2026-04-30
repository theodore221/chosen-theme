<?php
/**
 * chosen/stat-strip block — server-side render.
 *
 * Numbers count up from 0 to their target value when the section enters the
 * viewport. Counter runs over ~1500ms with easeOutQuart. Reduced-motion shows
 * the final value immediately. Inline IO snippet scoped to this instance.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$stats = isset( $attributes['stats'] ) && is_array( $attributes['stats'] )
	? array_values( array_filter( $attributes['stats'], static function ( $s ) {
		return is_array( $s ) && isset( $s['value'] ) && isset( $s['label'] );
	} ) )
	: [];

if ( empty( $stats ) ) {
	return;
}

$background = isset( $attributes['background'] ) ? (string) $attributes['background'] : 'paper';
$is_paper   = 'paper' === $background;

$bg_classes = $is_paper
	? 'bg-chosen-paper text-chosen-navy'
	: 'bg-chosen-royal text-white';

$col_classes = sprintf(
	'grid grid-cols-2 gap-10 md:grid-cols-%d md:gap-12',
	min( count( $stats ), 4 )
);

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-stat-strip py-20 md:py-24 ' . $bg_classes,
] );

$strip_id = 'chosen-stat-strip-' . wp_unique_id();
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> id="<?php echo esc_attr( $strip_id ); ?>">
	<div class="mx-auto max-w-wide px-6">
		<div class="<?php echo esc_attr( $col_classes ); ?>">
			<?php foreach ( $stats as $stat ) :
				$value  = (int) $stat['value'];
				$prefix = isset( $stat['prefix'] ) ? (string) $stat['prefix'] : '';
				$suffix = isset( $stat['suffix'] ) ? (string) $stat['suffix'] : '';
				$label  = isset( $stat['label'] ) ? (string) $stat['label'] : '';
			?>
				<div class="chosen-stat-strip__cell">
					<div class="chosen-stat-strip__value font-display text-[clamp(3rem,8vw,5.5rem)] leading-[0.95]"
						data-target="<?php echo esc_attr( $value ); ?>"
						data-prefix="<?php echo esc_attr( $prefix ); ?>"
						data-suffix="<?php echo esc_attr( $suffix ); ?>">
						<?php echo esc_html( $prefix . $value . $suffix ); ?>
					</div>
					<div class="mt-3 text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
						<?php echo esc_html( $label ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<script>
		( function () {
			var strip = document.getElementById( <?php echo wp_json_encode( $strip_id ); ?> );
			if ( ! strip || strip.dataset.statsReady ) { return; }
			strip.dataset.statsReady = '1';

			var prefersReduced = window.matchMedia &&
				window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

			var cells = strip.querySelectorAll( '.chosen-stat-strip__value' );

			function easeOutQuart( t ) { return 1 - Math.pow( 1 - t, 4 ); }

			function animate( cell ) {
				var target = parseInt( cell.dataset.target, 10 ) || 0;
				var prefix = cell.dataset.prefix || '';
				var suffix = cell.dataset.suffix || '';
				if ( prefersReduced || ! ( 'requestAnimationFrame' in window ) ) {
					cell.textContent = prefix + target + suffix;
					return;
				}
				var duration = 1500;
				var start = null;
				function step( ts ) {
					if ( start === null ) start = ts;
					var p = Math.min( 1, ( ts - start ) / duration );
					var current = Math.round( target * easeOutQuart( p ) );
					cell.textContent = prefix + current + suffix;
					if ( p < 1 ) requestAnimationFrame( step );
				}
				requestAnimationFrame( step );
			}

			if ( prefersReduced || ! ( 'IntersectionObserver' in window ) ) {
				cells.forEach( animate );
				return;
			}

			var io = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						animate( entry.target );
						io.unobserve( entry.target );
					}
				} );
			}, { threshold: 0.4 } );

			cells.forEach( function ( c ) {
				/* Reset to 0 before observation so the first value seen is the start, not the end. */
				c.textContent = ( c.dataset.prefix || '' ) + '0' + ( c.dataset.suffix || '' );
				io.observe( c );
			} );
		} )();
	</script>
</section>
