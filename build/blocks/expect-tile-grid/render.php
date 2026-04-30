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

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-expect-tile-grid bg-chosen-paper py-24 md:py-28',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $headline ) : ?>
			<h2 class="mt-4 max-w-2xl font-display text-[clamp(2rem,5vw,3.5rem)] leading-[1.05] uppercase text-chosen-navy">
				<?php echo esc_html( $headline ); ?>
			</h2>
		<?php endif; ?>

		<ul class="chosen-tile-list mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-5 lg:grid-cols-4" role="list">
			<?php foreach ( $tiles as $i => $tile ) :
				$featured  = ! empty( $tile['featured'] );
				$span_class = $featured ? 'lg:col-span-2' : 'lg:col-span-1';
				$pad_class  = $featured ? 'p-8 md:p-10' : 'p-6';
				$h_class    = $featured
					? 'font-display text-[clamp(1.75rem,3vw,2.25rem)] leading-tight uppercase'
					: 'font-sans text-[18px] font-bold leading-snug';
			?>
				<li class="chosen-tile chosen-fade-up relative overflow-hidden rounded-md border border-chosen-navy/10 bg-white transition-all duration-300 ease-out-quart <?php echo esc_attr( $span_class . ' ' . $pad_class ); ?>"
					style="--i: <?php echo (int) $i; ?>;">
					<h3 class="<?php echo esc_attr( $h_class ); ?> text-chosen-navy">
						<?php echo esc_html( $tile['title'] ); ?>
					</h3>
					<?php if ( ! empty( $tile['description'] ) ) : ?>
						<p class="<?php echo $featured ? 'mt-4 max-w-md text-[15px]' : 'mt-3 text-[14px]'; ?> leading-relaxed text-neutral-700">
							<?php echo esc_html( (string) $tile['description'] ); ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
