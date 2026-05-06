<?php
/**
 * chosen/story-section block — server-side render.
 *
 * Long-form editorial block. Eyebrow + oversized Anton heading + body
 * (rich text), with three layouts: stacked, split-photo-right,
 * split-photo-left. Heading enters via per-line split-text reveal
 * (data-split="line" wired up in parts/footer.html).
 *
 * Photos live at assets/img/photos-real/{stem}-{800|1280|1920}.{webp|jpg}
 * served via <picture><source>. WebP first, JPG fallback.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = isset( $attributes['eyebrow'] )    ? (string) $attributes['eyebrow']    : '';
$heading    = isset( $attributes['heading'] )    ? (string) $attributes['heading']    : '';
$body       = isset( $attributes['body'] )       ? (string) $attributes['body']       : '';
$layout     = isset( $attributes['layout'] )     && in_array( $attributes['layout'], [ 'stacked', 'split-photo-right', 'split-photo-left' ], true )
	? $attributes['layout']
	: 'stacked';
$photo_stem = isset( $attributes['photoStem'] )  ? preg_replace( '/[^a-z0-9_-]/', '', (string) $attributes['photoStem'] ) : 'dsc00635';
$photo_alt  = isset( $attributes['photoAlt'] )   ? (string) $attributes['photoAlt']   : '';
$bg         = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'paper', 'navy', 'white', 'cream', 'sage', 'sky', 'sun', 'coral' ], true )
	? $attributes['background']
	: 'paper';
$variant    = isset( $attributes['variant'] ) && 'theme' === $attributes['variant'] ? 'theme' : 'default';

if ( '' === trim( $heading ) ) {
	return;
}

$is_navy  = 'navy' === $bg;
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

$is_split = 'stacked' !== $layout;
$is_left  = 'split-photo-left' === $layout;

$layout_class = $is_split
	? 'chosen-story--split' . ( $is_left ? ' chosen-story--split-left' : '' )
	: 'chosen-story--stacked';

$theme_uri = get_theme_file_uri();
$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;

$is_theme = 'theme' === $variant;
$padding_y = $is_theme ? 'py-32 md:py-44' : 'py-24 md:py-32';
$variant_class = $is_theme ? ' chosen-story--theme' : '';
$heading_class = $is_theme ? 'chosen-display-2xl' : 'chosen-display-xl';

$anchor = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';

// Optional video background: theme/heading text flips to white over a navy scrim.
$video_stem  = isset( $attributes['videoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $attributes['videoStem'] ) : '';
$has_video   = '' !== $video_stem;
$theme_uri   = get_theme_file_uri();
$theme_path  = get_theme_file_path();
$video_v_mp4    = ( $has_video && file_exists( $theme_path . '/assets/video/' . $video_stem . '.mp4' ) )    ? filemtime( $theme_path . '/assets/video/' . $video_stem . '.mp4' )    : 0;
$video_v_webm   = ( $has_video && file_exists( $theme_path . '/assets/video/' . $video_stem . '.webm' ) )   ? filemtime( $theme_path . '/assets/video/' . $video_stem . '.webm' )   : 0;
$video_v_poster = ( $has_video && file_exists( $theme_path . '/assets/video/' . $video_stem . '-poster.jpg' ) ) ? filemtime( $theme_path . '/assets/video/' . $video_stem . '-poster.jpg' ) : 0;
$video_url_mp4    = $has_video ? $theme_uri . '/assets/video/' . $video_stem . '.mp4?v=' . $video_v_mp4 : '';
$video_url_webm   = $has_video ? $theme_uri . '/assets/video/' . $video_stem . '.webm?v=' . $video_v_webm : '';
$video_url_poster = $has_video ? $theme_uri . '/assets/video/' . $video_stem . '-poster.jpg?v=' . $video_v_poster : '';

// Video bg flips background-class to navy + makes section text white. Variant remains intact.
if ( $has_video ) {
	$bg_class = 'bg-chosen-navy text-white chosen-story--video';
	$is_navy  = true;
}

$wrapper_args = [
	'class' => 'chosen-story relative overflow-hidden ' . $bg_class . $variant_class . ' ' . $padding_y,
];
if ( $anchor ) {
	$wrapper_args['id'] = $anchor;
}
$wrapper_attrs = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php if ( $has_video ) : ?>
		<video
			class="chosen-story__video absolute inset-0 z-0 h-full w-full object-cover"
			autoplay
			muted
			loop
			playsinline
			preload="metadata"
			poster="<?php echo esc_url( $video_url_poster ); ?>"
			aria-hidden="true"
		>
			<?php if ( $video_v_webm ) : ?>
				<source src="<?php echo esc_url( $video_url_webm ); ?>" type="video/webm" />
			<?php endif; ?>
			<source src="<?php echo esc_url( $video_url_mp4 ); ?>" type="video/mp4" />
		</video>
		<div class="chosen-story__video-scrim absolute inset-0 z-[1] bg-gradient-to-b from-chosen-navy/55 via-chosen-navy/65 to-chosen-navy/85" aria-hidden="true"></div>
	<?php endif; ?>
	<?php if ( $is_theme ) : ?>
		<span class="chosen-story__ornament" aria-hidden="true"></span>
		<span class="chosen-story__breath" aria-hidden="true"></span>
	<?php endif; ?>
	<div class="relative z-[2] mx-auto max-w-wide px-6">
		<div class="<?php echo esc_attr( $layout_class ); ?>">
			<div class="chosen-story__copy chosen-fade-up">
				<?php if ( $eyebrow ) : ?>
					<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
						<?php echo esc_html( $eyebrow ); ?>
					</p>
					<span class="chosen-rule-grow mt-3 w-12" aria-hidden="true"></span>
				<?php endif; ?>

				<h2
					class="<?php echo esc_attr( $heading_class ); ?> mt-6 <?php echo $is_navy ? 'text-white' : 'text-chosen-navy'; ?>"
					data-split="line"
				>
					<?php echo esc_html( $heading ); ?>
				</h2>

				<div class="chosen-story__body mt-8 <?php echo $is_navy ? 'text-white/90' : 'text-chosen-navy/90'; ?>">
					<?php echo wp_kses_post( $body ); ?>
				</div>

				<?php if ( $is_theme ) : ?>
					<span class="chosen-rule-grow chosen-story__theme-rule mt-10 w-32 block" aria-hidden="true"></span>
				<?php endif; ?>
			</div>

			<?php if ( $is_split ) : ?>
				<div class="chosen-story__media chosen-fade-up">
					<picture>
						<source
							type="image/webp"
							srcset="<?php echo esc_url( $photo_base . '-800.webp' ); ?> 800w,
							        <?php echo esc_url( $photo_base . '-1280.webp' ); ?> 1280w,
							        <?php echo esc_url( $photo_base . '-1920.webp' ); ?> 1920w"
							sizes="(min-width: 1024px) 50vw, 100vw"
						/>
						<img
							src="<?php echo esc_url( $photo_base . '-1280.jpg' ); ?>"
							srcset="<?php echo esc_url( $photo_base . '-800.jpg' ); ?> 800w,
							        <?php echo esc_url( $photo_base . '-1280.jpg' ); ?> 1280w,
							        <?php echo esc_url( $photo_base . '-1920.jpg' ); ?> 1920w"
							sizes="(min-width: 1024px) 50vw, 100vw"
							alt="<?php echo esc_attr( $photo_alt ); ?>"
							loading="lazy"
							decoding="async"
						/>
					</picture>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
