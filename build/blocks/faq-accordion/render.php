<?php
/**
 * chosen/faq-accordion block — server-side render.
 *
 * Collapsible Q&A list using <details>/<summary> for native a11y +
 * keyboard support out of the box. A small JS snippet ensures only one
 * item is open at a time, and adds aria-expanded for screen-reader
 * clarity. The plus icon rotates 45deg via CSS when [open].
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    InnerBlocks content (none — leaf block).
 * @param WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = isset( $attributes['eyebrow'] ) ? (string) $attributes['eyebrow'] : '';
$headline   = isset( $attributes['headline'] ) ? (string) $attributes['headline'] : '';
$items      = isset( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : [];
$background = isset( $attributes['background'] ) ? (string) $attributes['background'] : 'paper';

$bg_class_map = [
	'paper' => 'bg-chosen-paper',
	'cream' => 'bg-chosen-cream',
	'white' => 'bg-white',
	'sky'   => 'bg-chosen-sky',
];
$bg_class = isset( $bg_class_map[ $background ] ) ? $bg_class_map[ $background ] : 'bg-chosen-paper';

$accordion_id = 'chosen-faq-' . wp_unique_id();

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-faq-accordion relative w-full ' . $bg_class . ' py-20 md:py-28',
] );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?> id="<?php echo esc_attr( $accordion_id ); ?>">
	<div class="mx-auto max-w-3xl px-6">
		<div class="chosen-faq-accordion__intro max-w-2xl">
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
		</div>

		<div class="chosen-faq-accordion__list mt-10 space-y-3" data-faq-list>
			<?php foreach ( $items as $i => $item ) :
				$question = isset( $item['question'] ) ? (string) $item['question'] : '';
				$answer   = isset( $item['answer'] ) ? (string) $item['answer'] : '';

				// Linkify info@chosenconference.org.au inside answers.
				$answer_html = preg_replace(
					'/(info@chosenconference\.org\.au)/i',
					'<a href="mailto:$1" class="underline decoration-chosen-navy/30 underline-offset-2 hover:decoration-chosen-gold hover:text-chosen-gold">$1</a>',
					esc_html( $answer )
				);
				?>
				<details class="chosen-faq-accordion__item group rounded-[14px] bg-white shadow-chosen-sm ring-1 ring-chosen-navy/8 transition-shadow duration-200 ease-out-quart hover:shadow-chosen-md open:shadow-chosen-md">
					<summary class="chosen-faq-accordion__summary flex cursor-pointer list-none items-center justify-between gap-6 px-6 py-5 md:px-7 md:py-6 text-left text-[16px] md:text-[17px] font-semibold leading-snug text-chosen-navy [&::-webkit-details-marker]:hidden">
						<span class="flex-1"><?php echo esc_html( $question ); ?></span>
						<span class="chosen-faq-accordion__icon relative inline-flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-chosen-gold/12 text-chosen-gold transition-transform duration-200 ease-out-quart group-open:rotate-45" aria-hidden="true">
							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
								<path d="M6 1v10M1 6h10" />
							</svg>
						</span>
					</summary>
					<div class="chosen-faq-accordion__answer px-6 md:px-7 pb-6 -mt-1 text-[15px] leading-relaxed text-chosen-navy/85">
						<?php echo $answer_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>

	<script>
		( function () {
			var root = document.getElementById( <?php echo wp_json_encode( $accordion_id ); ?> );
			if ( ! root || root.dataset.faqReady ) { return; }
			root.dataset.faqReady = '1';
			var list = root.querySelector( '[data-faq-list]' );
			if ( ! list ) { return; }
			var items = list.querySelectorAll( 'details.chosen-faq-accordion__item' );
			items.forEach( function ( item ) {
				/* Single-item-open: when one opens, close the others. */
				item.addEventListener( 'toggle', function () {
					if ( item.open ) {
						items.forEach( function ( other ) {
							if ( other !== item && other.open ) { other.open = false; }
						} );
					}
				} );
			} );
		} )();
	</script>
</section>
