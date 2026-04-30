import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-sponsor-strip-editor bg-chosen-paper p-10 rounded-md text-chosen-navy',
	} );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="p"
				value={ eyebrow }
				onChange={ ( v ) => setAttributes( { eyebrow: v } ) }
				placeholder={ __( 'Eyebrow…', 'chosen-theme' ) }
				style={ {
					color: '#EDA90C',
					fontSize: 11,
					fontWeight: 700,
					letterSpacing: '0.18em',
					textTransform: 'uppercase',
					margin: 0,
				} }
			/>
			<div
				style={ {
					marginTop: 24,
					padding: 24,
					border: '1px dashed rgba(11,10,85,0.3)',
					borderRadius: 8,
					textAlign: 'center',
					color: '#3F3B33',
					fontSize: 13,
				} }
			>
				{ __(
					'Sponsor logos are managed via the "Chosen Sponsors" field group below the editor (ACF). Add an image, sponsor name, and optional URL for each.',
					'chosen-theme'
				) }
			</div>
		</div>
	);
}
