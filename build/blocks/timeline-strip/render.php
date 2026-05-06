<?php
/**
 * chosen/timeline-strip block — server-side render.
 *
 * Horizontal milestone strip. Each milestone: gold/white top rule →
 * year (display type) → name → description. Marked-current milestone
 * gets the gold rule + gold year; others use a dim white rule. On
 * mobile, milestones stack vertically with a left border instead.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline   = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$subhead    = isset( $attributes['subhead'] ) ? (string) $attributes['subhead'] : '';
$milestones = isset( $attributes['milestones'] ) && is_array( $attributes['milestones'] ) ? $attributes['milestones'] : [];
$background = isset( $attributes['background'] ) ? (string) $attributes['background'] : 'navy';

$bg_class_map = [
	'navy'  => 'bg-chosen-navy text-white',
	'royal' => 'bg-chosen-royal text-white',
	'paper' => 'bg-chosen-paper text-chosen-navy',
	'cream' => 'bg-chosen-cream text-chosen-navy',
];
$bg_class    = isset( $bg_class_map[ $background ] ) ? $bg_class_map[ $background ] : 'bg-chosen-navy text-white';
$is_dark     = in_array( $background, [ 'navy', 'royal' ], true );
$base_text   = $is_dark ? 'text-white' : 'text-chosen-navy';
$muted_text  = $is_dark ? 'text-white/75' : 'text-chosen-navy/75';
$rule_dim    = $is_dark ? 'bg-white/25' : 'bg-chosen-navy/20';
$year_dim    = $is_dark ? 'text-white' : 'text-chosen-navy';
$desc_class  = $is_dark ? 'text-white/80' : 'text-chosen-navy/80';

$anchor = isset( $attributes['anchor'] ) ? (string) $attributes['anchor'] : '';
$wrapper_args = [
	'class' => 'chosen-timeline-strip relative w-full ' . $bg_class . ' py-20 md:py-28',
];
if ( $anchor ) {
	$wrapper_args['id'] = $anchor;
}
$wrapper_attrs = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto max-w-wide px-6">
		<div class="chosen-timeline-strip__intro max-w-3xl">
			<?php if ( $eyebrow ) : ?>
				<p class="text-[11px] font-bold uppercase tracking-eyebrow text-chosen-gold">
					<?php echo esc_html( $eyebrow ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $headline ) : ?>
				<h2 class="font-display mt-3 text-[clamp(2rem,4.5vw,3.5rem)] leading-[1.04] uppercase tracking-tight <?php echo esc_attr( $base_text ); ?>">
					<?php echo esc_html( $headline ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $subhead ) : ?>
				<p class="mt-5 max-w-2xl text-[16px] md:text-[17px] leading-relaxed <?php echo esc_attr( $muted_text ); ?>">
					<?php echo esc_html( $subhead ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="chosen-timeline-strip__nodes mt-14 grid gap-10 md:gap-6" style="grid-template-columns: repeat(1, 1fr);" data-md-cols="<?php echo esc_attr( max( 1, count( $milestones ) ) ); ?>">
			<style>
				@media (min-width: 768px) {
					.chosen-timeline-strip__nodes[data-md-cols="<?php echo esc_attr( max( 1, count( $milestones ) ) ); ?>"] {
						grid-template-columns: repeat(<?php echo esc_attr( max( 1, count( $milestones ) ) ); ?>, 1fr) !important;
					}
				}
			</style>
			<?php foreach ( $milestones as $milestone ) :
				$year        = isset( $milestone['year'] ) ? (string) $milestone['year'] : '';
				$name        = isset( $milestone['name'] ) ? (string) $milestone['name'] : '';
				$description = isset( $milestone['description'] ) ? (string) $milestone['description'] : '';
				$is_current  = ! empty( $milestone['current'] );
				$rule_class  = $is_current ? 'bg-chosen-gold' : $rule_dim;
				$year_class  = $is_current ? 'text-chosen-gold' : $year_dim;
				?>
				<div class="chosen-timeline-strip__node relative pt-6 md:pt-7">
					<span class="chosen-timeline-strip__rule absolute left-0 right-0 top-0 h-[2px] <?php echo esc_attr( $rule_class ); ?>" aria-hidden="true"></span>
					<?php if ( $is_current ) : ?>
						<span class="chosen-timeline-strip__dot absolute left-0 top-[-5px] inline-block h-3 w-3 rounded-full bg-chosen-gold ring-4 ring-chosen-gold/20" aria-hidden="true"></span>
					<?php endif; ?>
					<p class="font-display text-[clamp(2.75rem,5vw,4.25rem)] leading-[0.92] tracking-tight <?php echo esc_attr( $year_class ); ?>">
						<?php echo esc_html( $year ); ?>
					</p>
					<?php if ( $name ) : ?>
						<p class="mt-2 text-[15px] md:text-[16px] font-semibold leading-snug <?php echo esc_attr( $base_text ); ?>">
							<?php echo esc_html( $name ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<p class="mt-2 text-[14px] leading-relaxed <?php echo esc_attr( $desc_class ); ?>">
							<?php echo esc_html( $description ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
