import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const PHOTO_OPTIONS = [
	{ label: 'Adoration · monstrance (dsc00424)', value: 'dsc00424' },
	{ label: 'Anointing · priest blessing (dsc00155)', value: 'dsc00155' },
	{ label: 'Friendship · hugging (dsc00635)', value: 'dsc00635' },
	{ label: 'Brotherhood · arms around (dsc09854)', value: 'dsc09854' },
	{ label: 'Joy · UPPERROOM celebrating (dsc00143)', value: 'dsc00143' },
	{ label: 'Energy · pointing/laughing (dsc09433)', value: 'dsc09433' },
	{ label: 'Movement · corridor (dsc00238)', value: 'dsc00238' },
	{ label: 'Group · be radiant (img_4837)', value: 'img_4837' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, heading, body, layout, photoStem, photoAlt, background } = attributes;

	const isNavy = background === 'navy';
	const isWhite = background === 'white';
	const bgColor = isNavy ? '#0B0A55' : isWhite ? '#FFFFFF' : '#FAF8F3';
	const textColor = isNavy ? '#FFFFFF' : '#14130F';

	const blockProps = useBlockProps( {
		className: 'chosen-story-editor p-10',
		style: { backgroundColor: bgColor, color: textColor },
	} );

	const showPhoto = layout !== 'stacked';

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Layout', 'chosen-theme' ) }
						value={ layout }
						options={ [
							{ label: 'Stacked (heading + body, no photo)', value: 'stacked' },
							{ label: 'Split — photo right', value: 'split-photo-right' },
							{ label: 'Split — photo left', value: 'split-photo-left' },
						] }
						onChange={ ( v ) => setAttributes( { layout: v } ) }
					/>
					<SelectControl
						label={ __( 'Background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Warm paper', value: 'paper' },
							{ label: 'White', value: 'white' },
							{ label: 'Navy (dark)', value: 'navy' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				{ showPhoto && (
					<PanelBody title={ __( 'Photo', 'chosen-theme' ) }>
						<SelectControl
							label={ __( 'Photo', 'chosen-theme' ) }
							value={ photoStem }
							options={ PHOTO_OPTIONS }
							onChange={ ( v ) => setAttributes( { photoStem: v } ) }
						/>
						<TextControl
							label={ __( 'Alt text', 'chosen-theme' ) }
							value={ photoAlt }
							onChange={ ( v ) => setAttributes( { photoAlt: v } ) }
						/>
					</PanelBody>
				) }
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
					tagName="h2"
					value={ heading }
					onChange={ ( v ) => setAttributes( { heading: v } ) }
					placeholder={ __( 'Heading…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Anton, sans-serif',
						fontSize: 'clamp(56px, 12vw, 192px)',
						lineHeight: 0.92,
						letterSpacing: '-0.01em',
						textTransform: 'uppercase',
						margin: '20px 0 28px',
					} }
				/>
				<RichText
					tagName="div"
					value={ body }
					onChange={ ( v ) => setAttributes( { body: v } ) }
					placeholder={ __( 'Body…', 'chosen-theme' ) }
					multiline="p"
					style={ {
						maxWidth: '64ch',
						fontFamily: 'Work Sans, sans-serif',
						fontSize: 18,
						lineHeight: 1.65,
					} }
				/>
				{ showPhoto && (
					<p style={ { marginTop: 24, color: '#777', fontSize: 12 } }>
						{ __( 'Photo:', 'chosen-theme' ) } <code>{ photoStem }</code>
					</p>
				) }
			</div>
		</>
	);
}
