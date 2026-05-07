<?php
/**
 * chosen/pricing-tiers block — server-side render with dynamic early-bird pricing.
 *
 * Three-tier pricing card row + inclusions list + 3 callouts (group
 * discount, raffle, payment plans) + Register CTA. Tier cards are
 * lightly differentiated; the middle tier gets a subtle gold ring to
 * mark visual centre, but pricing isn't ranked — it's distance-based.
 *
 * Dynamic pricing model
 * ---------------------
 * Each tier carries two prices: `earlyBirdPrice` and `standardPrice`.
 * The block also has an `earlyBirdEndsDate` (default 2026-05-24,
 * Pentecost). On render, the server compares WP's current date to
 * that cutoff and decides which price to show:
 *
 *   - Today ≤ earlyBirdEndsDate → early-bird mode
 *       • Section heading carries an "Early bird" chip
 *       • Each tier shows the early-bird price big, with a small
 *         "Standard $X from 25 May 2026" sub-line
 *
 *   - Today > earlyBirdEndsDate → standard mode
 *       • No chip, no sub-line; just the standard price
 *
 * Backwards compat: if a tier still has the legacy single `price`
 * attribute and no early-bird/standard pair, that price renders as-is
 * with no early-bird messaging.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow            = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline           = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$subhead            = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$tiers              = isset( $attributes['tiers'] ) && is_array( $attributes['tiers'] ) ? $attributes['tiers'] : [];
$inclusions_heading = isset( $attributes['inclusionsHeading'] ) ? (string) $attributes['inclusionsHeading'] : '';
$inclusions         = isset( $attributes['inclusions'] ) && is_array( $attributes['inclusions'] ) ? $attributes['inclusions'] : [];
$callouts           = isset( $attributes['callouts'] ) && is_array( $attributes['callouts'] ) ? $attributes['callouts'] : [];
$cta_label          = isset( $attributes['ctaLabel'] ) ? (string) $attributes['ctaLabel'] : 'Register';
$background         = isset( $attributes['background'] ) ? (string) $attributes['background'] : 'paper';

// Early-bird config + date logic.
$early_bird_ends_raw = isset( $attributes['earlyBirdEndsDate'] ) ? (string) $attributes['earlyBirdEndsDate'] : '2026-05-24';
$early_bird_label    = isset( $attributes['earlyBirdLabel'] ) ? (string) $attributes['earlyBirdLabel'] : 'Early bird';
$early_bird_sub      = isset( $attributes['earlyBirdSubLabel'] ) ? (string) $attributes['earlyBirdSubLabel'] : '';

$today               = current_time( 'Y-m-d' );
$is_early_bird       = strcmp( $today, $early_bird_ends_raw ) <= 0;
$standard_from_human = '';
$ends_dt = DateTime::createFromFormat( 'Y-m-d', $early_bird_ends_raw );
if ( $ends_dt instanceof DateTime ) {
	$standard_starts = ( clone $ends_dt )->modify( '+1 day' );
	$standard_from_human = $standard_starts->format( 'j M Y' );
}

$bg_class_map = [
	'paper' => 'bg-chosen-paper',
	'cream' => 'bg-chosen-cream',
	'white' => 'bg-white',
	'sky'   => 'bg-chosen-sky',
	'aqua'  => 'bg-chosen-aqua',
];
$bg_class = isset( $bg_class_map[ $background ] ) ? $bg_class_map[ $background ] : 'bg-chosen-paper';

$register_url = ( defined( 'CHOSEN_REGISTER_URL' ) && CHOSEN_REGISTER_URL ) ? CHOSEN_REGISTER_URL : '';
$cta_disabled = '' === $register_url;

// Soft-tint backgrounds for the 3 callouts. Stays inside the brand palette.
$callout_bg_classes = [ 'bg-chosen-sun', 'bg-chosen-coral', 'bg-chosen-aqua' ];

$anchor = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';
$wrapper_args = [
	'class' => 'chosen-pricing-tiers relative w-full ' . $bg_class . ' py-20 md:py-28',
];
if ( $anchor ) {
	$wrapper_args['id'] = $anchor;
}
$wrapper_attrs = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<div class="chosen-pricing-tiers__intro max-w-2xl">
			<?php if ( $eyebrow ) : ?>
				<p class="flex flex-wrap items-center gap-x-3 gap-y-2 text-[11px] font-bold uppercase tracking-eyebrow text-chosen-gold">
					<span><?php echo esc_html( $eyebrow ); ?></span>
					<?php if ( $is_early_bird && $early_bird_label ) : ?>
						<span class="inline-flex items-center gap-2 rounded-full bg-chosen-gold px-3 py-[5px] text-[10px] font-bold uppercase tracking-[0.16em] text-chosen-navy">
							<span class="inline-block h-[6px] w-[6px] rounded-full bg-chosen-navy" aria-hidden="true"></span>
							<span><?php echo esc_html( $early_bird_label ); ?></span>
							<?php if ( $early_bird_sub ) : ?>
								<span class="hidden sm:inline text-chosen-navy/65"> · <?php echo esc_html( $early_bird_sub ); ?></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ( $headline ) : ?>
				<h2 class="font-display mt-3 max-w-3xl text-balance text-[clamp(2.25rem,5vw,4rem)] leading-[1.02] uppercase tracking-tight text-chosen-navy">
					<?php echo esc_html( $headline ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $subhead ) : ?>
				<p class="mt-5 max-w-xl text-[16px] md:text-[17px] leading-relaxed text-chosen-navy/85">
					<?php echo esc_html( $subhead ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="chosen-pricing-tiers__cards mt-12 grid gap-5 md:grid-cols-3 md:gap-6">
			<?php foreach ( $tiers as $i => $tier ) :
				$region        = isset( $tier['region'] ) ? (string) $tier['region'] : '';
				$description   = isset( $tier['description'] ) ? (string) $tier['description'] : '';
				$eb_price      = isset( $tier['earlyBirdPrice'] ) ? (string) $tier['earlyBirdPrice'] : '';
				$std_price     = isset( $tier['standardPrice'] ) ? (string) $tier['standardPrice'] : '';
				$legacy_price  = isset( $tier['price'] ) ? (string) $tier['price'] : '';

				// Resolve which price the card displays right now, plus whether
				// to show the supporting "Standard $X from …" sub-line.
				if ( $is_early_bird && '' !== $eb_price ) {
					$current_price       = $eb_price;
					$show_standard_note  = ( '' !== $std_price );
				} elseif ( '' !== $std_price ) {
					$current_price       = $std_price;
					$show_standard_note  = false;
				} else {
					// Backwards compatibility: legacy `price` field, or fallback.
					$current_price       = '' !== $legacy_price ? $legacy_price : $eb_price;
					$show_standard_note  = false;
				}

				$is_middle  = ( 1 === (int) $i );
				$ring_class = $is_middle ? ' ring-2 ring-chosen-gold/70' : ' ring-1 ring-chosen-navy/8';
				?>
				<article class="chosen-pricing-tiers__card relative flex flex-col items-start rounded-[18px] bg-white p-7 md:p-9 shadow-chosen-sm<?php echo esc_attr( $ring_class ); ?> transition-all duration-200 ease-out-quart hover:-translate-y-1 hover:shadow-chosen-md">
					<p class="text-[11px] font-bold uppercase tracking-eyebrow text-chosen-gold">
						<?php echo esc_html( $region ); ?>
					</p>
					<?php if ( $is_early_bird && '' !== $eb_price && $early_bird_label ) : ?>
						<p class="mt-2 inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-chosen-gold">
							<svg class="h-[10px] w-[10px]" viewBox="0 0 10 10" fill="currentColor" aria-hidden="true"><path d="M5 0l1.45 3.55L10 5l-3.55 1.45L5 10 3.55 6.45 0 5l3.55-1.45z"/></svg>
							<span><?php echo esc_html( $early_bird_label ); ?></span>
						</p>
					<?php endif; ?>
					<p class="font-display mt-3 text-[clamp(3rem,5.5vw,4.5rem)] leading-[0.95] tracking-tight text-chosen-navy">
						<?php echo esc_html( $current_price ); ?>
					</p>
					<?php if ( $show_standard_note && $standard_from_human ) : ?>
						<p class="mt-3 text-[12px] font-medium leading-snug text-chosen-navy/55">
							<?php
							/* translators: 1: standard price (e.g. $360), 2: human date (e.g. 25 May 2026) */
							echo esc_html( sprintf( __( 'Standard %1$s from %2$s', 'chosen-theme' ), $std_price, $standard_from_human ) );
							?>
						</p>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<p class="mt-3 text-[14px] leading-relaxed text-chosen-navy/75">
							<?php echo esc_html( $description ); ?>
						</p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $inclusions ) ) : ?>
			<div class="chosen-pricing-tiers__inclusions mt-10 rounded-[18px] bg-white p-7 md:p-9 shadow-chosen-sm ring-1 ring-chosen-navy/8">
				<?php if ( $inclusions_heading ) : ?>
					<p class="text-[12px] font-bold uppercase tracking-eyebrow text-chosen-navy">
						<?php echo esc_html( $inclusions_heading ); ?>
					</p>
				<?php endif; ?>
				<ul class="mt-5 grid gap-3 text-[15px] text-chosen-navy/85 sm:grid-cols-2">
					<?php foreach ( $inclusions as $inc ) : ?>
						<li class="flex items-start gap-3">
							<svg class="mt-[3px] h-4 w-4 flex-shrink-0 text-chosen-gold" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M3 8.5l3.2 3.2L13 4.8" />
							</svg>
							<span><?php echo esc_html( $inc ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $callouts ) ) : ?>
			<div class="chosen-pricing-tiers__callouts mt-8 grid gap-5 md:grid-cols-3">
				<?php foreach ( $callouts as $i => $callout ) :
					$title  = isset( $callout['title'] ) ? (string) $callout['title'] : '';
					$body   = isset( $callout['body'] ) ? (string) $callout['body'] : '';
					$bg_cls = $callout_bg_classes[ $i % count( $callout_bg_classes ) ];
					?>
					<div class="chosen-pricing-tiers__callout rounded-[18px] <?php echo esc_attr( $bg_cls ); ?> p-7 text-chosen-navy">
						<p class="font-display text-[1.5rem] leading-tight uppercase tracking-tight">
							<?php echo esc_html( $title ); ?>
						</p>
						<p class="mt-3 text-[14px] leading-relaxed text-chosen-navy/85">
							<?php
							// Linkify info@chosenconference.org.au mentions inside callout bodies.
							$linked_body = preg_replace(
								'/(info@chosenconference\.org\.au)/i',
								'<a href="mailto:$1" class="underline decoration-chosen-navy/30 underline-offset-2 hover:decoration-chosen-navy">$1</a>',
								esc_html( $body )
							);
							echo $linked_body; // phpcs:ignore WordPress.Security.EscapeOutput
							?>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="chosen-pricing-tiers__cta mt-10 flex justify-center md:justify-start">
			<a
				class="inline-flex items-center justify-center gap-2 rounded-full bg-chosen-gold px-8 py-4 text-[13px] font-bold uppercase tracking-[0.14em] text-chosen-navy no-underline shadow-[0_4px_18px_rgba(237,169,12,0.32)] transition-all duration-200 ease-out-quart hover:bg-chosen-gold-600 hover:text-white hover:shadow-[0_8px_24px_rgba(237,169,12,0.45)]"
				href="<?php echo esc_url( $register_url ? $register_url : '#' ); ?>"
				<?php if ( $cta_disabled ) : ?>
					aria-disabled="true"
					title="<?php esc_attr_e( 'Registration link not yet available', 'chosen-theme' ); ?>"
				<?php else : ?>
					rel="noopener"
				<?php endif; ?>
			>
				<span><?php echo esc_html( $cta_label ); ?></span>
				<svg width="14" height="11" viewBox="0 0 14 11" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M1.5 5.5h11M8 1l4.5 4.5L8 10" />
				</svg>
			</a>
		</div>
	</div>
</section>
