<?php
/**
 * chosen/hero block — server-side render.
 *
 * Composition: full-bleed video loop, content cluster anchored to the
 * bottom-left so the central subject of the video (faces, scripture in
 * hands) breathes in the upper half. Conference-led hierarchy:
 *
 *   1. Eyebrow      — "National Catholic Youth Conference"
 *   2. Wordmark     — CH ● SEN  (medallion replaces the centre glyph,
 *                     mirroring the 2026 Be Radiant poster lockup)
 *   3. Tagline      — "19–22 November 2026 · La Trobe University, Melbourne"
 *   4. Theme chip   — Theme · Be Radiant — Psalm 34:5  (the ONLY theme
 *                     touchpoint on the hero; small, paper bg, gold rule)
 *   5. CTA          — Register pill with arrow nudge
 *
 * Legibility: a vertical bottom-up gradient (transparent → navy) sits
 * behind the content cluster only — no global wash over the whole video.
 *
 * Migration note: legacy attributes `headlinePart1` / `headlinePart2`
 * (the old "Be" / "Radiant" composition) are honoured as a fallback if
 * `wordmarkPart1` is empty, so existing pages don't break.
 *
 * @param array    $attributes Block attributes from block.json defaults / editor input.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow         = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';

// Wordmark — new attributes preferred, legacy headlinePart1/2 honoured for back-compat.
$wordmark_part_1 = isset( $attributes['wordmarkPart1'] ) ? (string) $attributes['wordmarkPart1'] : '';
$wordmark_part_2 = isset( $attributes['wordmarkPart2'] ) ? (string) $attributes['wordmarkPart2'] : '';
if ( '' === $wordmark_part_1 && '' === $wordmark_part_2 ) {
	$legacy_1 = isset( $attributes['headlinePart1'] ) ? (string) $attributes['headlinePart1'] : '';
	$legacy_2 = isset( $attributes['headlinePart2'] ) ? (string) $attributes['headlinePart2'] : '';
	if ( '' !== $legacy_1 || '' !== $legacy_2 ) {
		$wordmark_part_1 = $legacy_1;
		$wordmark_part_2 = $legacy_2;
	} else {
		$wordmark_part_1 = 'CH';
		$wordmark_part_2 = 'SEN';
	}
}

$tagline         = isset( $attributes['tagline'] ) ? (string) $attributes['tagline'] : '';
$subhead         = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$theme_word_1    = isset( $attributes['themeWord1'] ) ? (string) $attributes['themeWord1'] : 'Be';
$theme_word_2    = isset( $attributes['themeWord2'] ) ? (string) $attributes['themeWord2'] : 'Radiant';
$theme_cite      = isset( $attributes['themeCite'] ) ? (string) $attributes['themeCite'] : 'Psalm 34:5';
// Legacy chip fallback — if the old chip attrs are set on a stored page, we
// still respect them (theme chip suppresses the new theme display headline).
$legacy_chip_label = isset( $attributes['themeChipLabel'] ) ? (string) $attributes['themeChipLabel'] : '';
$legacy_chip_cite  = isset( $attributes['themeChipCite'] )  ? (string) $attributes['themeChipCite']  : '';
$cta_label       = isset( $attributes['ctaLabel'] ) ? (string) $attributes['ctaLabel'] : 'Register';
$bg              = isset( $attributes['backgroundImage'] ) && is_array( $attributes['backgroundImage'] ) ? $attributes['backgroundImage'] : [];
$enable_rays     = ! empty( $attributes['enableRays'] );
$enable_kenburns = ! empty( $attributes['enableKenBurns'] );
$enable_video    = isset( $attributes['enableVideo'] ) ? ! empty( $attributes['enableVideo'] ) : true;

$bg_url = isset( $bg['url'] ) ? (string) $bg['url'] : '';
$bg_alt = isset( $bg['alt'] ) ? (string) $bg['alt'] : '';

$register_url = ( defined( 'CHOSEN_REGISTER_URL' ) && CHOSEN_REGISTER_URL ) ? CHOSEN_REGISTER_URL : '';
$cta_disabled = '' === $register_url;

$theme_uri    = get_theme_file_uri();
$theme_path   = get_theme_file_path();
// Cache-bust by file mtime so re-encoded loops aren't served from disk cache
// (browsers ignore the same-URL `<video>` change on hard refresh otherwise).
$video_v_mp4    = file_exists( $theme_path . '/assets/video/chosen-hero-loop.mp4' )    ? filemtime( $theme_path . '/assets/video/chosen-hero-loop.mp4' )    : 0;
$video_v_webm   = file_exists( $theme_path . '/assets/video/chosen-hero-loop.webm' )   ? filemtime( $theme_path . '/assets/video/chosen-hero-loop.webm' )   : 0;
$video_v_poster = file_exists( $theme_path . '/assets/video/chosen-hero-poster.jpg' ) ? filemtime( $theme_path . '/assets/video/chosen-hero-poster.jpg' ) : 0;
$video_mp4    = $theme_uri . '/assets/video/chosen-hero-loop.mp4?v=' . $video_v_mp4;
$video_webm   = $theme_uri . '/assets/video/chosen-hero-loop.webm?v=' . $video_v_webm;
$video_poster = $theme_uri . '/assets/video/chosen-hero-poster.jpg?v=' . $video_v_poster;

// If the template sets an anchor, use it as the section id so anchor links
// (e.g. nav targets) work AND the rays JS scope can find the same element.
$anchor  = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';
$hero_id = $anchor ? $anchor : 'chosen-hero-' . wp_unique_id();

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-hero relative isolate w-full overflow-hidden bg-chosen-navy text-white min-h-[88vh] md:min-h-[92vh]',
	'id'    => $hero_id,
] );

/**
 * Render a string as a sequence of <span> elements, one per character, each
 * with a transition-delay so the chars cascade in. The IO observer in
 * parts/footer.html toggles .is-visible on the wrapper and a CSS rule
 * propagates the visible state to descendants.
 *
 * @param string $text       The string to split.
 * @param int    $start_idx  Index of the first character (for global stagger across multiple words).
 * @return string HTML string of <span> elements.
 */
function chosen_hero_split_chars( $text, $start_idx = 0 ) {
	$out = '';
	$chars = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
	foreach ( $chars as $i => $ch ) {
		$delay = ( $start_idx + $i ) * 30;
		$out  .= sprintf(
			'<span class="chosen-split-char" style="transition-delay:%dms">%s</span>',
			$delay,
			esc_html( $ch )
		);
	}
	return $out;
}

$len_part_1 = mb_strlen( $wordmark_part_1 );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<?php if ( $enable_video ) : ?>
		<video
			class="chosen-hero__video absolute inset-0 z-0 h-full w-full object-cover"
			autoplay
			muted
			loop
			playsinline
			preload="metadata"
			poster="<?php echo esc_url( $video_poster ); ?>"
			aria-hidden="true"
		>
			<source src="<?php echo esc_url( $video_webm ); ?>" type="video/webm" />
			<source src="<?php echo esc_url( $video_mp4 ); ?>" type="video/mp4" />
		</video>
		<img
			class="chosen-hero__poster-fallback absolute inset-0 z-0 hidden h-full w-full object-cover"
			src="<?php echo esc_url( $video_poster ); ?>"
			alt=""
			aria-hidden="true"
			loading="eager"
			decoding="async"
		/>
	<?php elseif ( $bg_url ) : ?>
		<div class="chosen-hero__photo absolute inset-0 z-0<?php echo $enable_kenburns ? ' chosen-hero__photo--kenburns' : ''; ?>">
			<img src="<?php echo esc_url( $bg_url ); ?>"
				alt="<?php echo esc_attr( $bg_alt ); ?>"
				class="h-full w-full object-cover"
				loading="eager"
				decoding="async" />
		</div>
	<?php endif; ?>

	<div class="chosen-hero__top-fade absolute inset-x-0 top-0 z-[1] h-20 bg-gradient-to-b from-chosen-navy/40 to-transparent pointer-events-none" aria-hidden="true"></div>
	<div class="chosen-hero__bottom-scrim absolute inset-x-0 bottom-0 z-[1] h-[58%] bg-gradient-to-t from-chosen-navy via-chosen-navy/85 to-transparent pointer-events-none" aria-hidden="true"></div>

	<?php if ( $enable_rays ) : ?>
		<div class="chosen-hero__rays chosen-hero__rays--shift absolute inset-0 z-[1] pointer-events-none" aria-hidden="true" data-rays="18"></div>
	<?php endif; ?>

	<div class="chosen-hero__content relative z-[2] mx-auto flex min-h-[88vh] md:min-h-[92vh] max-w-wide flex-col justify-end px-6 pt-32 pb-10 md:pb-14">

		<div class="chosen-hero__primary">

			<h1 class="chosen-hero__wordmark chosen-wordmark chosen-wordmark--lockup chosen-fade-up font-sans text-[clamp(3rem,9vw,9rem)] font-bold leading-[0.95] uppercase tracking-[0.05em] text-white whitespace-nowrap">
				<?php echo chosen_hero_split_chars( $wordmark_part_1, 0 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="chosen-wordmark__o" aria-hidden="true"></span><?php echo chosen_hero_split_chars( $wordmark_part_2, $len_part_1 + 1 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h1>

			<?php if ( $eyebrow ) : ?>
				<p class="chosen-hero__eyebrow chosen-fade-up mt-5 flex items-center gap-3 md:gap-4 text-[13px] md:text-[16px] font-bold uppercase tracking-[0.22em] text-chosen-gold">
					<span class="inline-block h-px w-10 md:w-14 flex-shrink-0 bg-chosen-gold/70" aria-hidden="true"></span>
					<span><?php echo esc_html( $eyebrow ); ?></span>
				</p>
			<?php endif; ?>

			<?php if ( $legacy_chip_label ) : ?>
				<div class="chosen-fade-up mt-6">
					<span class="chosen-theme-chip" role="text">
						<span class="chosen-theme-chip__dot" aria-hidden="true"></span>
						<span><?php echo esc_html( $legacy_chip_label ); ?></span>
						<?php if ( $legacy_chip_cite ) : ?>
							<span class="chosen-theme-chip__rule" aria-hidden="true"></span>
							<span class="chosen-theme-chip__cite"><?php echo esc_html( $legacy_chip_cite ); ?></span>
						<?php endif; ?>
					</span>
				</div>
			<?php elseif ( $theme_word_1 || $theme_word_2 ) : ?>
				<div class="chosen-hero__theme chosen-fade-up mt-3 flex flex-wrap items-baseline gap-x-5 gap-y-2">
					<p class="chosen-hero__theme-word font-display text-[clamp(2rem,4.2vw,4.5rem)] leading-[0.95] uppercase">
						<?php if ( $theme_word_1 ) : ?>
							<span class="text-chosen-gold"><?php echo esc_html( $theme_word_1 ); ?></span>
						<?php endif; ?>
						<?php if ( $theme_word_2 ) : ?>
							<span class="<?php echo $theme_word_1 ? 'ml-2 ' : ''; ?>text-chosen-red"><?php echo esc_html( $theme_word_2 ); ?></span>
						<?php endif; ?>
					</p>
					<?php if ( $theme_cite ) : ?>
						<span class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold flex items-center gap-2">
							<span class="inline-block h-px w-6 bg-chosen-gold/60" aria-hidden="true"></span>
							<?php esc_html_e( 'Theme', 'chosen-theme' ); ?> · <?php echo esc_html( $theme_cite ); ?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $tagline ) : ?>
				<p class="chosen-hero__tagline chosen-fade-up mt-7 max-w-3xl text-[clamp(17px,2vw,24px)] font-semibold leading-snug text-white/95">
					<?php echo esc_html( $tagline ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $subhead ) : ?>
				<p class="chosen-hero__subhead chosen-fade-up mt-3 max-w-xl text-[16px] md:text-[17px] font-light italic leading-relaxed text-white/80">
					<?php echo esc_html( $subhead ); ?>
				</p>
			<?php endif; ?>

			<div class="chosen-fade-up mt-8">
				<a class="chosen-hero__cta chosen-cta-pulse inline-flex items-center justify-center gap-2 rounded-full bg-chosen-gold px-8 py-4 text-[13px] font-bold uppercase tracking-[0.14em] text-chosen-navy no-underline shadow-[0_4px_18px_rgba(237,169,12,0.32)] transition-all duration-200 ease-out-quart hover:bg-chosen-gold-600 hover:text-white hover:shadow-[0_8px_24px_rgba(237,169,12,0.45)]"
					href="<?php echo esc_url( $register_url ? $register_url : '#' ); ?>"
					<?php if ( $cta_disabled ) : ?>
						aria-disabled="true"
						title="<?php esc_attr_e( 'Registration link not yet available', 'chosen-theme' ); ?>"
					<?php else : ?>
						rel="noopener"
					<?php endif; ?>>
					<span><?php echo esc_html( $cta_label ); ?></span>
					<svg class="chosen-header__cta-arrow" width="14" height="11" viewBox="0 0 14 11" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M1.5 5.5h11M8 1l4.5 4.5L8 10" />
					</svg>
				</a>
			</div>
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
