<?php
/**
 * chosen/cta-banner block — server-side render.
 *
 * Full-width navy banner. Anton headline + gold pulse Register pill +
 * scripture pull-quote beneath. Reuses the chosenRegisterPulse keyframe
 * defined in src/css/input.css (introduced for chosen/hero).
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$headline  = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$subhead   = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$cta_label = isset( $attributes['ctaLabel'] ) ? (string) $attributes['ctaLabel'] : 'Register';
$eyebrow   = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$scripture = isset( $attributes['scripture'] ) ? (string) $attributes['scripture'] : '';
$cite      = isset( $attributes['cite'] ) ? (string) $attributes['cite'] : '';

$register_url = ( defined( 'CHOSEN_REGISTER_URL' ) && CHOSEN_REGISTER_URL ) ? CHOSEN_REGISTER_URL : '';
$cta_disabled = '' === $register_url;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-cta-banner bg-chosen-navy text-white py-20 md:py-28',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="chosen-fade-up mx-auto max-w-wide px-6">
		<div class="grid gap-12 md:grid-cols-2 md:items-end">
			<div>
				<?php if ( $headline ) : ?>
					<h2 class="font-display text-[clamp(2.5rem,7vw,5rem)] leading-[1.05] uppercase">
						<?php echo esc_html( $headline ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( $subhead ) : ?>
					<p class="mt-5 max-w-md text-[18px] font-light italic leading-relaxed text-white/85">
						<?php echo esc_html( $subhead ); ?>
					</p>
				<?php endif; ?>
				<div class="mt-8">
					<a class="chosen-cta-pulse inline-flex items-center justify-center rounded-full bg-chosen-gold px-7 py-3 text-[13px] font-bold uppercase tracking-[0.10em] text-chosen-navy no-underline transition-all duration-200 ease-out-quart hover:bg-chosen-gold-600 hover:text-white"
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

			<?php if ( $scripture || $cite || $eyebrow ) : ?>
				<aside class="md:border-l md:border-white/10 md:pl-12">
					<?php if ( $eyebrow ) : ?>
						<p class="text-[11px] font-bold uppercase tracking-[0.18em] text-chosen-gold">
							<?php echo esc_html( $eyebrow ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $scripture ) : ?>
						<blockquote class="mt-3 max-w-md text-[20px] font-light italic leading-relaxed text-white">
							<?php echo esc_html( $scripture ); ?>
						</blockquote>
					<?php endif; ?>
					<div class="mt-4 h-[3px] w-12 bg-chosen-gold" aria-hidden="true"></div>
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
