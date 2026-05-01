import {
	useBlockProps,
	InspectorControls,
	RichText,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	Button,
	TextControl,
	ToggleControl,
	Notice,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const ALLOWED_MEDIA = [ 'image' ];

export default function Edit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		wordmarkPart1,
		wordmarkPart2,
		tagline,
		subhead,
		themeWord1,
		themeWord2,
		themeCite,
		ctaLabel,
		backgroundImage,
		enableRays,
		enableKenBurns,
		enableVideo,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-hero-editor relative overflow-hidden bg-chosen-navy text-white p-8 rounded-md',
		style: backgroundImage?.url
			? {
					backgroundImage: `linear-gradient(rgba(11,10,85,0.6), rgba(11,10,85,0.6)), url(${ backgroundImage.url })`,
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					minHeight: '460px',
			  }
			: { minHeight: '460px' },
	} );

	const onSelectImage = ( media ) =>
		setAttributes( {
			backgroundImage: {
				id: media.id,
				url: media.url,
				alt: media.alt || '',
			},
		} );

	const altMissing =
		backgroundImage?.url && ! ( backgroundImage?.alt || '' ).trim();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Wordmark + tagline', 'chosen-theme' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Wordmark — left of medallion', 'chosen-theme' ) }
						value={ wordmarkPart1 }
						onChange={ ( v ) => setAttributes( { wordmarkPart1: v } ) }
						help={ __( 'Default: "CH". The Chosen medallion replaces the centre glyph.', 'chosen-theme' ) }
					/>
					<TextControl
						label={ __( 'Wordmark — right of medallion', 'chosen-theme' ) }
						value={ wordmarkPart2 }
						onChange={ ( v ) => setAttributes( { wordmarkPart2: v } ) }
						help={ __( 'Default: "SEN".', 'chosen-theme' ) }
					/>
					<TextControl
						label={ __( 'Tagline (dates · venue)', 'chosen-theme' ) }
						value={ tagline }
						onChange={ ( v ) => setAttributes( { tagline: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Theme line', 'chosen-theme' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Theme word — first (gold)', 'chosen-theme' ) }
						value={ themeWord1 }
						onChange={ ( v ) => setAttributes( { themeWord1: v } ) }
						help={ __( 'Default: "Be". Renders in display Anton, gold.', 'chosen-theme' ) }
					/>
					<TextControl
						label={ __( 'Theme word — second (red)', 'chosen-theme' ) }
						value={ themeWord2 }
						onChange={ ( v ) => setAttributes( { themeWord2: v } ) }
						help={ __( 'Default: "Radiant". Renders in display Anton, red.', 'chosen-theme' ) }
					/>
					<TextControl
						label={ __( 'Theme cite', 'chosen-theme' ) }
						value={ themeCite }
						onChange={ ( v ) => setAttributes( { themeCite: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Background', 'chosen-theme' ) } initialOpen={ false }>
					<ToggleControl
						label={ __( 'Use looping hero video', 'chosen-theme' ) }
						checked={ enableVideo }
						onChange={ ( v ) => setAttributes( { enableVideo: v } ) }
						help={ __( 'When enabled, the photo below is used only as a poster fallback (reduced-motion / slow connections).', 'chosen-theme' ) }
					/>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ onSelectImage }
							allowedTypes={ ALLOWED_MEDIA }
							value={ backgroundImage?.id }
							render={ ( { open } ) => (
								<>
									<Button variant="secondary" onClick={ open }>
										{ backgroundImage?.url
											? __( 'Replace photo', 'chosen-theme' )
											: __( 'Select photo', 'chosen-theme' ) }
									</Button>
									{ backgroundImage?.url && (
										<Button
											variant="link"
											isDestructive
											onClick={ () =>
												setAttributes( {
													backgroundImage: { id: 0, url: '', alt: '' },
												} )
											}
											style={ { marginLeft: 8 } }
										>
											{ __( 'Remove', 'chosen-theme' ) }
										</Button>
									) }
								</>
							) }
						/>
					</MediaUploadCheck>
					{ backgroundImage?.url && (
						<div style={ { marginTop: 12 } }>
							<TextControl
								label={ __( 'Alt text (required for accessibility)', 'chosen-theme' ) }
								value={ backgroundImage.alt || '' }
								onChange={ ( alt ) =>
									setAttributes( { backgroundImage: { ...backgroundImage, alt } } )
								}
								help={ __(
									'Describe what is happening in the photo. Required for screen readers.',
									'chosen-theme'
								) }
							/>
							{ altMissing && (
								<Notice status="warning" isDismissible={ false }>
									{ __( 'Alt text is required before publishing.', 'chosen-theme' ) }
								</Notice>
							) }
						</div>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Motion', 'chosen-theme' ) } initialOpen={ false }>
					<ToggleControl
						label={ __( 'Rotating radiant rays behind hero', 'chosen-theme' ) }
						checked={ enableRays }
						onChange={ ( v ) => setAttributes( { enableRays: v } ) }
						help={ __(
							'Off automatically when the visitor has reduced-motion preference.',
							'chosen-theme'
						) }
					/>
					<ToggleControl
						label={ __( 'Slow Ken Burns on poster image', 'chosen-theme' ) }
						checked={ enableKenBurns }
						onChange={ ( v ) => setAttributes( { enableKenBurns: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'CTA', 'chosen-theme' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Button label', 'chosen-theme' ) }
						value={ ctaLabel }
						onChange={ ( v ) => setAttributes( { ctaLabel: v } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div style={ { position: 'relative', zIndex: 2, maxWidth: 880 } }>
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
					<h1
						style={ {
							fontFamily: 'Work Sans, sans-serif',
							fontWeight: 700,
							fontSize: 96,
							lineHeight: 0.95,
							letterSpacing: '0.05em',
							margin: '14px 0 0',
							textTransform: 'uppercase',
						} }
					>
						<span>{ wordmarkPart1 || 'CH' }</span>
						<span style={ {
							display: 'inline-block',
							width: '0.84em',
							height: '0.84em',
							verticalAlign: '-0.18em',
							margin: '0 0.04em',
							background: '#EDA90C',
							borderRadius: '9999px',
						} } aria-hidden="true" />
						<span>{ wordmarkPart2 || 'SEN' }</span>
					</h1>
					<p style={ {
						fontSize: 22,
						fontWeight: 600,
						margin: '20px 0 0',
						color: 'rgba(255,255,255,0.95)',
					} }>{ tagline }</p>
					<RichText
						tagName="p"
						value={ subhead }
						onChange={ ( v ) => setAttributes( { subhead: v } ) }
						placeholder={ __( 'Optional subhead…', 'chosen-theme' ) }
						style={ {
							fontStyle: 'italic',
							fontWeight: 300,
							fontSize: 16,
							margin: '8px 0 24px',
							color: 'rgba(255,255,255,0.8)',
						} }
					/>
					{ ( themeWord1 || themeWord2 ) && (
						<div style={ { display: 'flex', flexWrap: 'wrap', alignItems: 'baseline', gap: 16, marginTop: 16 } }>
							<p style={ {
								fontFamily: 'Anton, sans-serif',
								fontSize: 64,
								lineHeight: 0.95,
								margin: 0,
								textTransform: 'uppercase',
							} }>
								{ themeWord1 && <span style={ { color: '#EDA90C' } }>{ themeWord1 }</span> }
								{ themeWord2 && <span style={ { color: '#F71A1D', marginLeft: themeWord1 ? 8 : 0 } }>{ themeWord2 }</span> }
							</p>
							{ themeCite && (
								<span style={ {
									fontSize: 11, fontWeight: 700, letterSpacing: '0.18em',
									textTransform: 'uppercase', color: '#EDA90C',
								} }>Theme · { themeCite }</span>
							) }
						</div>
					) }
					<div style={ { marginTop: 24 } }>
						<RichText
							tagName="span"
							value={ ctaLabel }
							onChange={ ( v ) => setAttributes( { ctaLabel: v } ) }
							placeholder="Register"
							style={ {
								display: 'inline-block',
								background: '#EDA90C',
								color: '#0B0A55',
								padding: '14px 28px',
								borderRadius: 9999,
								fontWeight: 700,
								fontSize: 13,
								letterSpacing: '0.14em',
								textTransform: 'uppercase',
							} }
						/>
					</div>
				</div>
				{ ! backgroundImage?.url && (
					<div style={ {
						position: 'absolute', bottom: 12, right: 16,
						fontSize: 11, color: 'rgba(255,255,255,0.5)', fontStyle: 'italic',
					} }>
						{ __( 'No poster set — Inspector → Background.', 'chosen-theme' ) }
					</div>
				) }
			</div>
		</>
	);
}
