<?php
/**
 * chosen/cta-banner block — server-side render.
 *
 * Bolder pass: full-bleed photo background with navy gradient scrim,
 * oversized Anton headline (clamp 56→160px), strengthened register-pulse
 * gold pill, and an SVG arrow that slides in on CTA hover.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$headline      = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$subhead       = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$cta_label     = isset( $attributes['ctaLabel'] ) ? (string) $attributes['ctaLabel'] : 'Register';
$cta_href_raw  = isset( $attributes['ctaHref'] ) ? trim( (string) $attributes['ctaHref'] ) : '';
$eyebrow       = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$scripture     = isset( $attributes['scripture'] ) ? (string) $attributes['scripture'] : '';
$cite          = isset( $attributes['cite'] ) ? (string) $attributes['cite'] : '';
$enable_photo  = ! isset( $attributes['enablePhoto'] ) || ! empty( $attributes['enablePhoto'] );
$photo_stem    = isset( $attributes['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $attributes['photoStem'] ) : 'dsc00424';

$register_url = ( defined( 'CHOSEN_REGISTER_URL' ) && CHOSEN_REGISTER_URL ) ? CHOSEN_REGISTER_URL : '';
// Explicit ctaHref wins (mailto:, anchor, secondary page CTA). Otherwise fall back to the register constant.
$cta_href     = '' !== $cta_href_raw ? $cta_href_raw : $register_url;
$cta_disabled = '' === $cta_href;
$cta_is_mailto = 0 === strpos( $cta_href, 'mailto:' );

$theme_uri = get_theme_file_uri();
$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-cta-banner relative isolate overflow-hidden bg-chosen-navy text-white py-32 md:py-44',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php if ( $enable_photo ) : ?>
		<picture>
			<source
				type="image/webp"
				srcset="<?php echo esc_url( $photo_base . '-1280.webp' ); ?> 1280w,
				        <?php echo esc_url( $photo_base . '-1920.webp' ); ?> 1920w"
				sizes="100vw"
			/>
			<img
				class="absolute inset-0 z-0 h-full w-full object-cover"
				style="object-position: center 35%"
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
		<!-- Polished scrim: top 35% scrim catches the bright ceiling/highlights so
		     headline reads cleanly; bottom anchors deeply into navy so the CTA pill
		     and citation read against a calm wash. Two-stop gradient avoids the
		     muddy mid-tone the previous 3-stop produced on lighter scenes. -->
		<div class="absolute inset-0 z-[1] bg-gradient-to-b from-chosen-navy/40 via-chosen-navy/20 to-chosen-navy/95" aria-hidden="true"></div>
		<div class="absolute inset-x-0 bottom-0 z-[1] h-2/3 bg-gradient-to-t from-chosen-navy to-transparent" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="chosen-fade-up relative z-[2] mx-auto max-w-wide px-6">
		<div class="grid gap-12 md:grid-cols-2 md:items-end">
			<div>
				<?php if ( $headline ) : ?>
					<h2 class="chosen-display-xl uppercase" data-split="line">
						<?php echo esc_html( $headline ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( $subhead ) : ?>
					<p class="mt-7 max-w-md text-[18px] font-light italic leading-relaxed text-white/85">
						<?php echo esc_html( $subhead ); ?>
					</p>
				<?php endif; ?>
				<div class="mt-10">
					<a class="chosen-cta-arrow chosen-cta-pulse group inline-flex items-center justify-center gap-2 rounded-full bg-chosen-gold px-8 py-4 text-[14px] font-bold uppercase tracking-[0.10em] text-chosen-navy no-underline transition-all duration-200 ease-out-quart hover:bg-chosen-gold-600 hover:text-white"
						href="<?php echo $cta_is_mailto ? esc_attr( $cta_href ) : esc_url( $cta_href ? $cta_href : '#' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>"
						<?php if ( $cta_disabled ) : ?>
							aria-disabled="true"
							title="<?php esc_attr_e( 'Link not yet available', 'chosen-theme' ); ?>"
						<?php else : ?>
							rel="noopener"
						<?php endif; ?>>
						<span><?php echo esc_html( $cta_label ); ?></span>
						<svg class="chosen-cta-arrow__svg" width="18" height="14" viewBox="0 0 18 14" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M2 7h14M11 2l5 5-5 5" />
						</svg>
					</a>
				</div>
			</div>

			<?php if ( $scripture || $cite || $eyebrow ) : ?>
				<aside class="md:border-l md:border-white/15 md:pl-12">
					<?php if ( $eyebrow ) : ?>
						<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
							<?php echo esc_html( $eyebrow ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $scripture ) : ?>
						<blockquote class="mt-4 max-w-md text-[22px] font-light italic leading-relaxed text-white" data-split="line">
							<?php echo esc_html( $scripture ); ?>
						</blockquote>
					<?php endif; ?>
					<span class="chosen-rule-grow mt-6 w-12 block" aria-hidden="true"></span>
					<?php if ( $cite ) : ?>
						<cite class="mt-3 inline-block not-italic text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
							<?php echo esc_html( $cite ); ?>
						</cite>
					<?php endif; ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
