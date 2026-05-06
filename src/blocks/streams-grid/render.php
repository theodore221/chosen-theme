<?php
/**
 * chosen/streams-grid block — server-side render.
 *
 * Three audience streams as side-by-side cards. Each card: stream name
 * (display type) → age band (gold eyebrow) → intro paragraph → topics
 * list with gold dot bullets. Cards stack to single column on mobile.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline   = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$subhead    = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$streams    = isset( $attributes['streams'] ) && is_array( $attributes['streams'] ) ? $attributes['streams'] : [];
$background = isset( $attributes['background'] ) ? (string) $attributes['background'] : 'cream';

$bg_class_map = [
	'paper' => 'bg-chosen-paper',
	'cream' => 'bg-chosen-cream',
	'white' => 'bg-white',
	'sage'  => 'bg-chosen-sage',
	'sky'   => 'bg-chosen-sky',
	'sun'   => 'bg-chosen-sun',
	'aqua'  => 'bg-chosen-aqua',
];
$bg_class = isset( $bg_class_map[ $background ] ) ? $bg_class_map[ $background ] : 'bg-chosen-cream';

$anchor = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';
$wrapper_args = [
	'class' => 'chosen-streams-grid relative w-full ' . $bg_class . ' py-20 md:py-28',
];
if ( $anchor ) {
	$wrapper_args['id'] = $anchor;
}
$wrapper_attrs = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<div class="chosen-streams-grid__intro max-w-2xl">
			<?php if ( $eyebrow ) : ?>
				<p class="text-[11px] font-bold uppercase tracking-eyebrow text-chosen-gold">
					<?php echo esc_html( $eyebrow ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $headline ) : ?>
				<h2 class="font-display mt-3 text-[clamp(2.25rem,5vw,4rem)] leading-[1.02] uppercase tracking-tight text-chosen-navy">
					<?php echo esc_html( $headline ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $subhead ) : ?>
				<p class="mt-5 max-w-xl text-[16px] md:text-[17px] leading-relaxed text-chosen-navy/85">
					<?php echo esc_html( $subhead ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="chosen-streams-grid__cards mt-12 grid gap-6 md:grid-cols-3 md:gap-8">
			<?php
			$theme_uri = get_theme_file_uri();
			foreach ( $streams as $stream ) :
				$name           = isset( $stream['name'] ) ? (string) $stream['name'] : '';
				$age_band       = isset( $stream['ageBand'] ) ? (string) $stream['ageBand'] : '';
				$intro          = isset( $stream['intro'] ) ? (string) $stream['intro'] : '';
				$topics         = isset( $stream['topics'] ) && is_array( $stream['topics'] ) ? $stream['topics'] : [];
				$photo_stem     = isset( $stream['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $stream['photoStem'] ) : '';
				$photo_position = isset( $stream['photoPosition'] ) ? preg_replace( '/[^a-zA-Z0-9% .-]/', '', (string) $stream['photoPosition'] ) : 'center center';
				$has_photo      = '' !== $photo_stem;
				$photo_base     = $theme_uri . '/assets/img/photos-real/' . $photo_stem;
				?>
				<article class="chosen-streams-grid__card group relative flex h-full flex-col overflow-hidden rounded-[18px] bg-white shadow-chosen-sm ring-1 ring-chosen-navy/8 transition-all duration-300 ease-out-quart hover:-translate-y-1 hover:shadow-chosen-md">
					<?php if ( $has_photo ) : ?>
						<div class="chosen-streams-grid__media relative aspect-[4/3] overflow-hidden">
							<picture>
								<source type="image/webp"
									srcset="<?php echo esc_url( $photo_base . '-800.webp' ); ?> 800w, <?php echo esc_url( $photo_base . '-1280.webp' ); ?> 1280w"
									sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" />
								<img class="chosen-streams-grid__photo h-full w-full object-cover transition-transform duration-700 ease-out-quart group-hover:scale-[1.04]"
									src="<?php echo esc_url( $photo_base . '-1280.jpg' ); ?>"
									srcset="<?php echo esc_url( $photo_base . '-800.jpg' ); ?> 800w, <?php echo esc_url( $photo_base . '-1280.jpg' ); ?> 1280w"
									sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
									style="object-position: <?php echo esc_attr( $photo_position ); ?>"
									alt=""
									loading="lazy"
									decoding="async"
									aria-hidden="true" />
							</picture>
							<span class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-chosen-navy/35 to-transparent" aria-hidden="true"></span>
							<?php if ( $age_band ) : ?>
								<span class="absolute left-5 top-5 inline-block rounded-full bg-white/95 px-3 py-1 text-[10px] font-bold uppercase tracking-eyebrow text-chosen-navy shadow-chosen-sm">
									<?php echo esc_html( $age_band ); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="chosen-streams-grid__body relative flex flex-1 flex-col p-7 md:p-8">
						<?php if ( ! $has_photo ) : ?>
							<span class="chosen-streams-grid__accent absolute left-7 right-7 top-0 h-[3px] rounded-b-full bg-chosen-gold" aria-hidden="true"></span>
						<?php endif; ?>
						<h3 class="font-display text-[clamp(1.85rem,3vw,2.4rem)] leading-[0.98] uppercase tracking-tight text-chosen-navy">
							<?php echo esc_html( $name ); ?>
						</h3>
						<?php if ( $age_band && ! $has_photo ) : ?>
							<p class="mt-2 text-[11px] font-bold uppercase tracking-eyebrow text-chosen-gold">
								<?php echo esc_html( $age_band ); ?>
							</p>
						<?php endif; ?>
						<?php if ( $intro ) : ?>
							<p class="chosen-streams-grid__intro-body mt-4 text-[15px] leading-relaxed text-chosen-navy/85">
								<?php echo esc_html( $intro ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $topics ) ) : ?>
							<ul class="chosen-streams-grid__topics mt-6 space-y-2 border-t border-chosen-navy/10 pt-5 text-[14px] text-chosen-navy/85">
								<?php foreach ( $topics as $topic ) : ?>
									<li class="flex items-start gap-3">
										<span class="mt-[7px] inline-block h-[6px] w-[6px] flex-shrink-0 rounded-full bg-chosen-gold" aria-hidden="true"></span>
										<span><?php echo esc_html( $topic ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
