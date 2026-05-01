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

$layout = isset( $attributes['layout'] ) && in_array( $attributes['layout'], [ 'bento', 'even', 'asymmetric-9' ], true )
	? $attributes['layout']
	: 'asymmetric-9';

$grid_class = [
	'bento'         => 'chosen-image-mosaic--bento',
	'even'          => 'chosen-image-mosaic--even',
	'asymmetric-9'  => 'chosen-image-mosaic--asymmetric-9',
][ $layout ];

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-image-mosaic py-12 md:py-16 ' . $grid_class,
] );

/* Stagger directions cycle through L/R/U/D so each tile arrives from a
   different vector; produces a non-uniform but rhythmic entrance. */
$dirs = [ 'l', 'r', 'u', 'l', 'u', 'r', 'u', 'l', 'r' ];
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> aria-label="<?php esc_attr_e( 'Photo gallery from Chosen', 'chosen-theme' ); ?>">
	<ul class="chosen-image-mosaic__grid" role="list">
		<?php foreach ( $images as $i => $img ) :
			$url     = (string) $img['url'];
			$alt     = isset( $img['alt'] )     ? (string) $img['alt']     : '';
			$caption = isset( $img['caption'] ) ? (string) $img['caption'] : '';
			$dir     = isset( $dirs[ $i ] ) ? $dirs[ $i ] : 'u';
		?>
			<li class="chosen-image-mosaic__cell chosen-fade-up"
				data-index="<?php echo (int) $i; ?>"
				data-from="<?php echo esc_attr( $dir ); ?>"
				style="--i: <?php echo (int) $i; ?>;">
				<img class="chosen-image-mosaic__img"
					src="<?php echo esc_url( $url ); ?>"
					alt="<?php echo esc_attr( $alt ); ?>"
					loading="lazy"
					decoding="async"
					onload="this.setAttribute('data-loaded','1')" />
				<?php if ( $caption ) : ?>
					<div class="chosen-image-mosaic__caption" aria-hidden="true">
						<span class="chosen-image-mosaic__caption-rule"></span>
						<span class="chosen-image-mosaic__caption-label"><?php echo esc_html( $caption ); ?></span>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
