import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const PHOTO_OPTIONS = [
	{ label: 'dsc00143', value: 'dsc00143' },
	{ label: 'dsc00155', value: 'dsc00155' },
	{ label: 'dsc00238', value: 'dsc00238' },
	{ label: 'dsc00424', value: 'dsc00424' },
	{ label: 'dsc00635', value: 'dsc00635' },
	{ label: 'dsc09433', value: 'dsc09433' },
	{ label: 'dsc09854', value: 'dsc09854' },
	{ label: 'img_4837', value: 'img_4837' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { quote, name, ageCity, photoStem, background } = attributes;
	const isNavy = background === 'navy';
	const bgColor = isNavy ? '#0B0A55' : background === 'white' ? '#FFFFFF' : '#FAF8F3';
	const textColor = isNavy ? '#FFFFFF' : '#14130F';

	const blockProps = useBlockProps( {
		className: 'chosen-testimonial-editor',
		style: { backgroundColor: bgColor, color: textColor, padding: 40, position: 'relative' },
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Testimonial', 'chosen-theme' ) }>
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
					<SelectControl
						label={ __( 'Photo', 'chosen-theme' ) }
						value={ photoStem }
						options={ PHOTO_OPTIONS }
						onChange={ ( v ) => setAttributes( { photoStem: v } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<span style={ {
					position: 'absolute',
					top: -20,
					left: 16,
					fontFamily: 'Anton, sans-serif',
					fontSize: 180,
					lineHeight: 1,
					color: '#EDA90C',
					opacity: 0.12,
				} } aria-hidden="true">“</span>
				<RichText
					tagName="blockquote"
					value={ quote }
					onChange={ ( v ) => setAttributes( { quote: v } ) }
					placeholder={ __( 'Pull-quote…', 'chosen-theme' ) }
					style={ {
						position: 'relative',
						fontFamily: 'Work Sans, sans-serif',
						fontSize: 24,
						lineHeight: 1.45,
						fontStyle: 'italic',
						fontWeight: 300,
						margin: 0,
					} }
				/>
				<div style={ { display: 'flex', alignItems: 'center', gap: 14, marginTop: 24 } }>
					<div style={ {
						width: 48,
						height: 48,
						borderRadius: '9999px',
						background: '#0B0A55',
						color: '#fff',
						display: 'flex',
						alignItems: 'center',
						justifyContent: 'center',
						fontFamily: 'Anton, sans-serif',
					} }>{ name?.charAt( 0 ) || '·' }</div>
					<div>
						<RichText
							tagName="p"
							value={ name }
							onChange={ ( v ) => setAttributes( { name: v } ) }
							placeholder={ __( 'Name…', 'chosen-theme' ) }
							style={ { margin: 0, fontWeight: 600, fontSize: 14 } }
						/>
						<RichText
							tagName="p"
							value={ ageCity }
							onChange={ ( v ) => setAttributes( { ageCity: v } ) }
							placeholder={ __( 'Age · City…', 'chosen-theme' ) }
							style={ { margin: '4px 0 0', fontSize: 11, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#EDA90C' } }
						/>
					</div>
				</div>
			</div>
		</>
	);
}
