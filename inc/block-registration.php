<?php
/**
 * Register the "Chosen" block category and all custom blocks.
 *
 * Blocks live under src/blocks/<slug>/ and are registered from their
 * block.json metadata. To add a new block: scaffold via /new-block <slug>,
 * then append the slug to the $blocks array in chosen_register_blocks().
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add a "Chosen" category at the top of the block inserter.
 *
 * @param array $categories Existing categories.
 * @return array
 */
function chosen_register_block_category( $categories ) {
    return array_merge(
        [
            [
                'slug'  => 'chosen',
                'title' => __( 'Chosen', 'chosen-theme' ),
                'icon'  => null,
            ],
        ],
        $categories
    );
}
add_filter( 'block_categories_all', 'chosen_register_block_category', 10, 1 );

/**
 * Register all chosen/* blocks from src/blocks/<slug>/block.json.
 */
function chosen_register_blocks() {
    $blocks = [
        'hero',
        'marquee',
        'vision',
        'stat-strip',
        'expect-tile-grid',
        'streams-grid',
        'pricing-tiers',
        'faq-accordion',
        'timeline-strip',
        'image-mosaic',
        'cta-banner',
        'sponsor-strip',
        'quote',
        'story-section',
        'testimonial',
    ];

    // wp-scripts compiles src/blocks/<slug>/ → build/blocks/<slug>/. The compiled
    // bundle contains the resolved JS, the copied block.json, and render.php — that's
    // what register_block_type() needs to read.
    foreach ( $blocks as $slug ) {
        $path = __DIR__ . '/../build/blocks/' . $slug;
        if ( file_exists( $path . '/block.json' ) ) {
            register_block_type( $path );
        }
    }
}
add_action( 'init', 'chosen_register_blocks' );
