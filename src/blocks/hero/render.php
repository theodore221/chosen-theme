<?php
/**
 * chosen/hero block — server-side render.
 *
 * @param array    $attributes Block attributes from block.json defaults / editor input.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow         = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline_part_1 = isset( $attributes['headlinePart1'] ) ? (string) $attributes['headlinePart1'] : 'Be';
$headline_part_2 = isset( $attributes['headlinePart2'] ) ? (string) $attributes['headlinePart2'] : 'Radiant';
$subhead         = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$date_range      = isset( $attributes['dateRange'] ) ? (string) $attributes['dateRange'] : '';
$date_month      = isset( $attributes['dateMonth'] ) ? (string) $attributes['dateMonth'] : '';
$venue_name      = isset( $attributes['venueName'] ) ? (string) $attributes['venueName'] : '';
$venue_city      = isset( $attributes['venueCity'] ) ? (string) $attributes['venueCity'] : '';
$cta_label       = isset( $attributes['ctaLabel'] ) ? (string) $attributes['ctaLabel'] : 'Register';
$bg              = isset( $attributes['backgroundImage'] ) && is_array( $attributes['backgroundImage'] ) ? $attributes['backgroundImage'] : [];
$enable_rays     = ! empty( $attributes['enableRays'] );
$enable_kenburns = ! empty( $attributes['enableKenBurns'] );

$bg_url = isset( $bg['url'] ) ? (string) $bg['url'] : '';
$bg_alt = isset( $bg['alt'] ) ? (string) $bg['alt'] : '';

$register_url = ( defined( 'CHOSEN_REGISTER_URL' ) && CHOSEN_REGISTER_URL ) ? CHOSEN_REGISTER_URL : '';
$cta_disabled = '' === $register_url;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-hero relative isolate w-full overflow-hidden bg-chosen-navy text-white',
] );

// Unique id so inline JS can scope to this instance only.
$hero_id = 'chosen-hero-' . wp_unique_id();
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> id="<?php echo esc_attr( $hero_id ); ?>">
	<?php if ( $bg_url ) : ?>
		<div class="chosen-hero__photo absolute inset-0 z-0<?php echo $enable_kenburns ? ' chosen-hero__photo--kenburns' : ''; ?>">
			<img src="<?php echo esc_url( $bg_url ); ?>"
				alt="<?php echo esc_attr( $bg_alt ); ?>"
				class="h-full w-full object-cover"
				loading="eager"
				decoding="async" />
		</div>
		<div class="absolute inset-0 z-[1] bg-chosen-navy/60" aria-hidden="true"></div>
	<?php endif; ?>

	<?php if ( $enable_rays ) : ?>
		<div class="chosen-hero__rays absolute inset-0 z-[1] pointer-events-none" aria-hidden="true" data-rays="18"></div>
	<?php endif; ?>

	<div class="chosen-hero__content relative z-[2] mx-auto flex min-h-[88vh] max-w-wide flex-col items-center justify-center px-6 py-24 text-center md:min-h-[92vh]">

		<?php if ( $eyebrow ) : ?>
			<p class="chosen-hero__eyebrow text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
		<?php endif; ?>

		<h1 class="chosen-hero__headline mt-6 font-display text-[clamp(4.5rem,14vw,9rem)] leading-[0.92] uppercase tracking-tight">
			<span class="text-chosen-gold"><?php echo esc_html( $headline_part_1 ); ?></span>
			<span class="ml-3 text-chosen-red"><?php echo esc_html( $headline_part_2 ); ?></span>
		</h1>

		<?php if ( $subhead ) : ?>
			<p class="chosen-hero__subhead mt-6 max-w-xl text-[18px] font-light italic leading-relaxed text-white/85">
				<?php echo esc_html( $subhead ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $date_range || $venue_name ) : ?>
			<div class="chosen-hero__datevenue mt-10 hidden md:flex flex-col items-stretch gap-4 sm:flex-row sm:items-center sm:gap-5">
				<?php if ( $date_range ) : ?>
					<div class="chosen-hero__date rounded-md bg-chosen-gold px-5 py-3 text-left font-display leading-[0.95] text-chosen-navy">
						<div class="text-[44px] sm:text-[56px]"><?php echo esc_html( $date_range ); ?></div>
						<?php if ( $date_month ) : ?>
							<div class="mt-1 text-[14px] tracking-[0.04em] sm:text-[16px]"><?php echo esc_html( $date_month ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( $venue_name ) : ?>
					<div class="chosen-hero__venue text-left">
						<div class="text-[10px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
							<?php esc_html_e( 'Venue', 'chosen-theme' ); ?>
						</div>
						<div class="mt-1 text-[18px] font-bold text-white sm:text-[20px]"><?php echo esc_html( $venue_name ); ?></div>
						<?php if ( $venue_city ) : ?>
							<div class="text-[13px] text-white/70 sm:text-[14px]"><?php echo esc_html( $venue_city ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="mt-10">
			<a class="chosen-hero__cta chosen-cta-pulse inline-flex items-center justify-center rounded-full bg-chosen-gold px-7 py-3 text-[13px] font-bold uppercase tracking-[0.10em] text-chosen-navy no-underline transition-all duration-200 ease-out-quart hover:bg-chosen-gold-600 hover:text-white"
				href="<?php echo esc_url( $register_url ? $register_url : '#' ); ?>"
				<?php if ( $cta_disabled ) : ?>
					aria-disabled="true"
					title="<?php esc_attr_e( 'Registration link not yet available', 'chosen-theme' ); ?>"
				<?php else : ?>
					rel="noopener"
				<?php endif; ?>>
				<?php echo esc_html( $cta_label ); ?>
			</a>
		</div>
	</div>

	<?php if ( $enable_rays ) : ?>
		<script>
			( function () {
				var hero = document.getElementById( <?php echo wp_json_encode( $hero_id ); ?> );
				if ( ! hero ) { return; }
				var holder = hero.querySelector( '.chosen-hero__rays' );
				if ( ! holder || holder.dataset.raysReady ) { return; }
				holder.dataset.raysReady = '1';
				var n = parseInt( holder.dataset.rays || '18', 10 );
				/* Quieter palette: 4 theological radiance colours only (gold/orange/red/royal),
				   ordered to feel like a warm dawn rather than a kaleidoscope. */
				var colors = [ '#EDA90C', '#FE4E0E', '#F71A1D', '#4071AC' ];
				var frag = document.createDocumentFragment();
				for ( var i = 0; i < n; i++ ) {
					var ray = document.createElement( 'span' );
					ray.className = 'chosen-hero__ray';
					ray.style.background = colors[ i % colors.length ];
					ray.style.transform = 'rotate(' + ( ( 360 / n ) * i ) + 'deg)';
					frag.appendChild( ray );
				}
				holder.appendChild( frag );
			} )();
		</script>
	<?php endif; ?>
</section>
