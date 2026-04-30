<?php
/**
 * ACF field group for the chosen/sponsor-strip block.
 *
 * Registered as PHP (rather than DB-only) so the schema deploys with the
 * theme folder. ACF Pro is not required — repeater + image fields work
 * with the free version that ships with this theme stack.
 *
 * @package chosen-theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( [
	'key'    => 'group_chosen_sponsors',
	'title'  => 'Chosen Sponsors',
	'fields' => [
		[
			'key'           => 'field_chosen_sponsors_repeater',
			'name'          => 'sponsors',
			'label'         => 'Sponsors',
			'type'          => 'repeater',
			'instructions'  => 'Add each partner / sponsor logo. Drag to reorder.',
			'min'           => 0,
			'max'           => 12,
			'layout'        => 'block',
			'button_label'  => '+ Add sponsor',
			'sub_fields'    => [
				[
					'key'           => 'field_chosen_sponsor_logo',
					'name'          => 'logo',
					'label'         => 'Logo',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'mime_types'    => 'jpg,jpeg,png,svg,webp',
					'required'      => 1,
				],
				[
					'key'      => 'field_chosen_sponsor_name',
					'name'     => 'name',
					'label'    => 'Sponsor name (used as alt text)',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'           => 'field_chosen_sponsor_url',
					'name'          => 'url',
					'label'         => 'URL (optional)',
					'type'          => 'url',
					'instructions'  => 'If provided, the logo links to this URL in a new tab.',
					'required'      => 0,
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'chosen/sponsor-strip',
			],
		],
	],
] );
