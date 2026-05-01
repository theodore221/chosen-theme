<?php
/**
 * chosen/people-grid block — server-side render.
 *
 * Circular-headshot grid for team/leaders. Cards stagger in via .chosen-fade-up
 * (--i index drives transition-delay). Hover reveals a navy scrim with the
 * member name + role label. Photos pulled from assets/img/photos-real/{stem}-*
 * and rendered via <picture>.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$heading = isset( $attributes['heading'] ) ? (string) $attributes['heading'] : '';
$members = isset( $attributes['members'] ) && is_array( $attributes['members'] ) ? $attributes['members'] : [];
$bg      = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'paper', 'white', 'navy', 'cream', 'sage', 'sky', 'sun', 'coral' ], true )
	? $attributes['background']
	: 'paper';

if ( empty( $members ) ) {
	return;
}

$bg_class = [
	'paper' => 'bg-chosen-paper',
	'white' => 'bg-white',
	'navy'  => 'bg-chosen-navy text-white',
	'cream' => 'bg-chosen-cream',
	'sage'  => 'bg-chosen-sage',
	'sky'   => 'bg-chosen-sky',
	'sun'   => 'bg-chosen-sun',
	'coral' => 'bg-chosen-coral',
][ $bg ];

$is_navy = 'navy' === $bg;
$theme_uri = get_theme_file_uri();

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-people ' . $bg_class . ' py-24 md:py-32',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<?php if ( $eyebrow ) : ?>
			<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $heading ) : ?>
			<h2
				class="chosen-display-xl mt-4 <?php echo $is_navy ? 'text-white' : 'text-chosen-navy'; ?>"
				data-split="line"
			>
				<?php echo esc_html( $heading ); ?>
			</h2>
		<?php endif; ?>

		<ul class="chosen-people-grid mt-16 list-none p-0">
			<?php foreach ( $members as $i => $m ) :
				$name      = isset( $m['name'] )      ? (string) $m['name']      : '';
				$role      = isset( $m['role'] )      ? (string) $m['role']      : '';
				$photo_alt = isset( $m['photoAlt'] )  ? (string) $m['photoAlt']  : ( $name ? $name . ' portrait' : 'Portrait' );
				$photo_stem = isset( $m['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $m['photoStem'] ) : 'dsc09854';
				$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;
			?>
				<li class="chosen-people-grid__card chosen-fade-up" style="--i: <?php echo (int) $i; ?>">
					<div class="chosen-people-grid__photo">
						<picture>
							<source type="image/webp" srcset="<?php echo esc_url( $photo_base . '-800.webp' ); ?>" />
							<img
								src="<?php echo esc_url( $photo_base . '-800.jpg' ); ?>"
								alt="<?php echo esc_attr( $photo_alt ); ?>"
								loading="lazy"
								decoding="async"
								width="200"
								height="200"
							/>
						</picture>
						<div class="chosen-people-grid__scrim" aria-hidden="true">
							<span class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold"><?php echo esc_html( $role ); ?></span>
							<span class="mt-1 text-[18px] font-semibold leading-tight"><?php echo esc_html( $name ); ?></span>
						</div>
					</div>
					<p class="text-[16px] font-semibold mt-2 <?php echo $is_navy ? 'text-white' : 'text-chosen-navy'; ?>">
						<?php echo esc_html( $name ); ?>
					</p>
					<p class="mt-1 text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
						<?php echo esc_html( $role ); ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
