<?php
/**
 * chosen/image-mosaic block — server-side render.
 *
 * Hard-edged photo mosaic. Bento layout: tiles 0 and 3 span 2x2 (the heroes);
 * the rest are 1x1. Even layout: uniform 3-col grid.
 *
 * Hover: 6%-opacity gold tint overlay via ::after pseudo, fades in 200ms.
 * Lazy-load via loading="lazy" on the <img>, with a CSS blur-to-sharp
 * transition handled by inline `onload` setting `data-loaded="1"`.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$images = isset( $attributes['images'] ) && is_array( $attributes['images'] )
	? array_values( array_filter( $attributes['images'], static function ( $img ) {
		return is_array( $img ) && ! empty( $img['url'] );
	} ) )
	: [];

if ( empty( $images ) ) {
	return;
}

$layout = isset( $attributes['layout'] ) && in_array( $attributes['layout'], [ 'bento', 'even' ], true )
	? $attributes['layout']
	: 'bento';

$grid_class = 'bento' === $layout
	? 'chosen-image-mosaic--bento'
	: 'chosen-image-mosaic--even';

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-image-mosaic py-12 md:py-16 ' . $grid_class,
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Photo gallery from Chosen', 'chosen-theme' ); ?>">
	<ul class="chosen-image-mosaic__grid" role="list">
		<?php foreach ( $images as $i => $img ) :
			$url = (string) $img['url'];
			$alt = isset( $img['alt'] ) ? (string) $img['alt'] : '';
		?>
			<li class="chosen-image-mosaic__cell" data-index="<?php echo (int) $i; ?>">
				<img class="chosen-image-mosaic__img"
					src="<?php echo esc_url( $url ); ?>"
					alt="<?php echo esc_attr( $alt ); ?>"
					loading="lazy"
					decoding="async"
					onload="this.setAttribute('data-loaded','1')" />
			</li>
		<?php endforeach; ?>
	</ul>
</section>
