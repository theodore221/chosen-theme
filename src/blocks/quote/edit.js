import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, verse, cite, background } = attributes;
	const isPaper = background === 'paper';

	const blockProps = useBlockProps( {
		className: `chosen-quote-editor p-10 rounded-md ${
			isPaper ? 'bg-chosen-paper text-chosen-navy' : 'bg-chosen-navy text-white'
		}`,
		style: {
			backgroundColor: isPaper ? '#FAF8F3' : '#0B0A55',
			color: isPaper ? '#0B0A55' : '#FFFFFF',
		},
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Background', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Section background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Navy (dark)', value: 'navy' },
							{ label: 'Warm paper (light)', value: 'paper' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
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
				<RichText
					tagName="blockquote"
					value={ verse }
					onChange={ ( v ) => setAttributes( { verse: v } ) }
					placeholder={ __( 'Scripture verse…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Work Sans, sans-serif',
						fontStyle: 'italic',
						fontWeight: 300,
						fontSize: 28,
						lineHeight: 1.4,
						margin: '14px 0 0',
						maxWidth: 720,
					} }
				/>
				<div
					style={ {
						width: 48,
						height: 3,
						background: '#EDA90C',
						marginTop: 18,
					} }
				/>
				<RichText
					tagName="cite"
					value={ cite }
					onChange={ ( v ) => setAttributes( { cite: v } ) }
					placeholder={ __( 'Cite…', 'chosen-theme' ) }
					style={ {
						display: 'inline-block',
						fontStyle: 'normal',
						color: '#EDA90C',
						fontSize: 11,
						fontWeight: 700,
						letterSpacing: '0.18em',
						textTransform: 'uppercase',
						marginTop: 12,
					} }
				/>
			</div>
		</>
	);
}
