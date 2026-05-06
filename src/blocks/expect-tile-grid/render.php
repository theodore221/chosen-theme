<?php
/**
 * chosen/expect-tile-grid block — server-side render.
 *
 * Grid of cards. 4-col desktop, 2-col tablet, 1-col mobile. Each card has
 * a hover state that lifts (-4px) and slides in a 4px gold top accent
 * (::before pseudo). Stagger entrance via per-tile --i custom property.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$tiles    = isset( $attributes['tiles'] ) && is_array( $attributes['tiles'] )
	? array_values( array_filter( $attributes['tiles'], static function ( $t ) {
		return is_array( $t ) && isset( $t['title'] ) && '' !== trim( (string) $t['title'] );
	} ) )
	: [];

if ( empty( $tiles ) ) {
	return;
}

$anchor = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';
$wrapper_args = [
	'class' => 'chosen-expect-tile-grid bg-chosen-paper py-24 md:py-28',
];
if ( $anchor ) {
	$wrapper_args['id'] = $anchor;
}
$wrapper_attrs = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $headline ) : ?>
			<h2 class="chosen-display-xl mt-6 max-w-5xl text-chosen-navy" data-split="line">
				<?php echo esc_html( $headline ); ?>
			</h2>
		<?php endif; ?>

		<ul class="chosen-tile-list mt-16 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-5 lg:grid-cols-4" role="list">
			<?php
			$theme_uri = get_theme_file_uri();
			foreach ( $tiles as $i => $tile ) :
				$featured  = ! empty( $tile['featured'] );
				$span_class = $featured ? 'lg:col-span-2' : 'lg:col-span-1';
				$pad_class  = $featured ? 'p-8 md:p-12 min-h-[320px] flex flex-col justify-end' : 'p-6';
				$h_class    = $featured
					? 'chosen-display-xl text-[clamp(2rem,4vw,3.5rem)] leading-[0.95]'
					: 'font-sans text-[18px] font-bold leading-snug';
				$photo_stem = isset( $tile['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $tile['photoStem'] ) : '';
				$has_photo  = $featured && '' !== $photo_stem;
				$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;
				/* Optional per-tile object-position (CSS) so editors can shift
				   the focal point of cropped featured photos — e.g. "center 70%"
				   biases the crop toward the bottom of the source image. */
				$photo_position = isset( $tile['photoPosition'] ) ? preg_replace( '/[^a-zA-Z0-9% .-]/', '', (string) $tile['photoPosition'] ) : '';
				$tile_color_class = $has_photo ? 'text-white' : 'text-chosen-navy';
				$tile_bg_class    = $has_photo ? 'bg-chosen-navy' : 'bg-white';
			?>
				<li class="chosen-tile chosen-fade-up relative overflow-hidden rounded-md border border-chosen-navy/10 transition-all duration-300 ease-out-quart <?php echo esc_attr( $span_class . ' ' . $pad_class . ' ' . $tile_bg_class ); ?>"
					style="--i: <?php echo (int) $i; ?>;">
					<?php if ( $has_photo ) : ?>
						<picture>
							<source type="image/webp" srcset="<?php echo esc_url( $photo_base . '-1280.webp' ); ?>" />
							<img class="chosen-tile__photo absolute inset-0 z-0 h-full w-full object-cover transition-transform duration-500 ease-out-quart"
								<?php if ( $photo_position ) : ?>style="object-position: <?php echo esc_attr( $photo_position ); ?>"<?php endif; ?>
								src="<?php echo esc_url( $photo_base . '-1280.jpg' ); ?>"
								alt=""
								loading="lazy"
								decoding="async"
								aria-hidden="true" />
						</picture>
						<div class="absolute inset-0 z-[1] bg-gradient-to-t from-chosen-navy via-chosen-navy/70 to-chosen-navy/30" aria-hidden="true"></div>
					<?php endif; ?>
					<div class="<?php echo $has_photo ? 'relative z-[2]' : ''; ?>">
						<h3 class="<?php echo esc_attr( $h_class . ' ' . $tile_color_class ); ?>">
							<?php echo esc_html( $tile['title'] ); ?>
						</h3>
						<?php if ( ! empty( $tile['description'] ) ) : ?>
							<p class="<?php echo $featured ? 'mt-4 max-w-md text-[15px]' : 'mt-3 text-[14px]'; ?> leading-relaxed <?php echo $has_photo ? 'text-white/90' : 'text-neutral-700'; ?>">
								<?php echo esc_html( (string) $tile['description'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
