<?php
/**
 * chosen/stat-strip block — server-side render.
 *
 * Asymmetric layout: the FIRST stat in the array renders as the "hero" stat
 * (~1.6× the size of the supporting stats), spanning 2 grid columns on
 * desktop. Supporting stats stay 1-col. This breaks the "row of equal cells"
 * AI-template pattern. Optional eyebrow above the strip orients the reader.
 *
 * Numbers count up from 0 to value when the section enters viewport
 * (~1500ms easeOutQuart). Reduced-motion shows finals immediately. Inline
 * IO snippet scoped per-instance via wp_unique_id().
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';

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
$is_photo   = 'photo' === $background;

$bg_classes = $is_paper
	? 'bg-chosen-paper text-chosen-navy'
	: ( $is_photo
		? 'bg-chosen-navy text-white relative overflow-hidden'
		: 'bg-chosen-royal text-white' );

// Eyebrow stays gold on all backgrounds.
// Supporting label colour: gold on paper, white/85 on royal/photo.
$label_class = $is_paper ? 'text-chosen-gold' : 'text-white/85';

$photo_stem = isset( $attributes['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $attributes['photoStem'] ) : 'img_4837';
$theme_uri  = get_theme_file_uri();
$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-stat-strip py-16 md:py-28 ' . $bg_classes,
] );

$strip_id = 'chosen-stat-strip-' . wp_unique_id();
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> id="<?php echo esc_attr( $strip_id ); ?>">
	<?php if ( $is_photo ) : ?>
		<picture>
			<source
				type="image/webp"
				srcset="<?php echo esc_url( $photo_base . '-1280.webp' ); ?> 1280w,
				        <?php echo esc_url( $photo_base . '-1920.webp' ); ?> 1920w"
				sizes="100vw"
			/>
			<img
				class="absolute inset-0 z-0 h-full w-full object-cover"
				src="<?php echo esc_url( $photo_base . '-1280.jpg' ); ?>"
				srcset="<?php echo esc_url( $photo_base . '-1280.jpg' ); ?> 1280w,
				        <?php echo esc_url( $photo_base . '-1920.jpg' ); ?> 1920w"
				sizes="100vw"
				alt=""
				loading="lazy"
				decoding="async"
				aria-hidden="true"
			/>
		</picture>
		<div class="absolute inset-0 z-[1] bg-chosen-navy/70" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="mx-auto max-w-wide px-6 <?php echo $is_photo ? 'relative z-[2]' : ''; ?>">
		<?php if ( $eyebrow ) : ?>
			<p class="mb-10 text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold md:mb-14">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<div class="grid grid-cols-1 gap-10 md:grid-cols-4 md:items-end md:gap-x-12 md:gap-y-12">
			<?php foreach ( $stats as $i => $stat ) :
				$display_text = isset( $stat['displayText'] ) ? (string) $stat['displayText'] : '';
				$has_text     = '' !== trim( $display_text );
				$value        = (int) ( isset( $stat['value'] ) ? $stat['value'] : 0 );
				$prefix       = isset( $stat['prefix'] ) ? (string) $stat['prefix'] : '';
				$suffix       = isset( $stat['suffix'] ) ? (string) $stat['suffix'] : '';
				$label        = isset( $stat['label'] ) ? (string) $stat['label'] : '';
				$featured     = ( 0 === $i );
				$cell_classes = $featured
					? 'chosen-stat-strip__cell chosen-stat-strip__cell--featured md:col-span-2'
					: 'chosen-stat-strip__cell md:col-span-1';
				$value_size   = $featured
					? 'text-[clamp(5rem,14vw,15rem)]'
					: 'text-[clamp(3rem,7vw,5.5rem)]';
			?>
				<div class="<?php echo esc_attr( $cell_classes ); ?>">
					<?php if ( $has_text ) : ?>
						<div class="chosen-stat-strip__value chosen-stat-strip__value--text font-display <?php echo esc_attr( $value_size ); ?> leading-[0.92] tracking-tight">
							<?php echo esc_html( $display_text ); ?>
						</div>
					<?php else : ?>
						<div class="chosen-stat-strip__value chosen-stat__num font-display <?php echo esc_attr( $value_size ); ?> leading-[0.92] tracking-tight"
							data-target="<?php echo esc_attr( $value ); ?>"
							data-prefix="<?php echo esc_attr( $prefix ); ?>"
							data-suffix="<?php echo esc_attr( $suffix ); ?>">
							<?php echo esc_html( $prefix . $value . $suffix ); ?>
						</div>
					<?php endif; ?>
					<div class="mt-3 text-[11px] font-bold uppercase tracking-[0.18em] <?php echo esc_attr( $label_class ); ?>">
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

			/* Only animate stats that have a numeric target. Text-only stats
			   (rendered as .chosen-stat-strip__value--text) skip count-up. */
			var cells = strip.querySelectorAll( '.chosen-stat-strip__value:not(.chosen-stat-strip__value--text)' );

			function easeOutQuart( t ) { return 1 - Math.pow( 1 - t, 4 ); }

			function pop( cell ) {
				/* Brief scale-up at count-completion. Class swap forces animation
				   restart if the cell is somehow re-triggered. */
				cell.classList.remove( 'is-popped' );
				/* Force reflow so the re-add fires the animation. */
				void cell.offsetWidth;
				cell.classList.add( 'is-popped' );
			}

			function animate( cell ) {
				var target = parseInt( cell.dataset.target, 10 ) || 0;
				var prefix = cell.dataset.prefix || '';
				var suffix = cell.dataset.suffix || '';
				/* Skip count-up for trivially small targets (1-9) — animating
				   0 → 5 over 1500ms looks broken, not impressive. */
				if ( prefersReduced || target < 10 || ! ( 'requestAnimationFrame' in window ) ) {
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
					if ( p < 1 ) {
						requestAnimationFrame( step );
					} else if ( ! prefersReduced ) {
						pop( cell );
					}
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
				var t = parseInt( c.dataset.target, 10 ) || 0;
				/* Reset to 0 only for cells that will actually animate.
				   Otherwise leave the static value in place. */
				if ( t >= 10 ) {
					c.textContent = ( c.dataset.prefix || '' ) + '0' + ( c.dataset.suffix || '' );
				}
				io.observe( c );
			} );
		} )();
	</script>
</section>
