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
		headlinePart1,
		headlinePart2,
		subhead,
		dateRange,
		dateMonth,
		venueName,
		venueCity,
		ctaLabel,
		backgroundImage,
		enableRays,
		enableKenBurns,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-hero-editor relative overflow-hidden bg-chosen-navy text-white p-8 rounded-md',
		style: backgroundImage?.url
			? {
					backgroundImage: `linear-gradient(rgba(11,10,85,0.6), rgba(11,10,85,0.6)), url(${ backgroundImage.url })`,
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					minHeight: '420px',
			  }
			: { minHeight: '420px' },
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
				<PanelBody title={ __( 'Background photo', 'chosen-theme' ) } initialOpen={ true }>
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
													backgroundImage: {
														id: 0,
														url: '',
														alt: '',
													},
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
									setAttributes( {
										backgroundImage: { ...backgroundImage, alt },
									} )
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
				<PanelBody title={ __( 'Date & venue', 'chosen-theme' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Date range', 'chosen-theme' ) }
						value={ dateRange }
						onChange={ ( v ) => setAttributes( { dateRange: v } ) }
					/>
					<TextControl
						label={ __( 'Date month', 'chosen-theme' ) }
						value={ dateMonth }
						onChange={ ( v ) => setAttributes( { dateMonth: v } ) }
					/>
					<TextControl
						label={ __( 'Venue name', 'chosen-theme' ) }
						value={ venueName }
						onChange={ ( v ) => setAttributes( { venueName: v } ) }
					/>
					<TextControl
						label={ __( 'Venue city', 'chosen-theme' ) }
						value={ venueCity }
						onChange={ ( v ) => setAttributes( { venueCity: v } ) }
					/>
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
						label={ __( 'Slow Ken Burns on photo', 'chosen-theme' ) }
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
				<div
					style={ {
						position: 'relative',
						zIndex: 2,
						maxWidth: 800,
						margin: '0 auto',
						textAlign: 'center',
					} }
				>
					<RichText
						tagName="p"
						value={ eyebrow }
						onChange={ ( v ) => setAttributes( { eyebrow: v } ) }
						placeholder={ __( 'Eyebrow text…', 'chosen-theme' ) }
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
							fontFamily: 'Anton, sans-serif',
							fontSize: 96,
							lineHeight: 0.95,
							margin: '12px 0 8px',
							textTransform: 'uppercase',
						} }
					>
						<RichText
							tagName="span"
							value={ headlinePart1 }
							onChange={ ( v ) => setAttributes( { headlinePart1: v } ) }
							placeholder="Be"
							style={ { color: '#EDA90C', marginRight: '0.18em' } }
						/>
						<RichText
							tagName="span"
							value={ headlinePart2 }
							onChange={ ( v ) => setAttributes( { headlinePart2: v } ) }
							placeholder="Radiant"
							style={ { color: '#F71A1D' } }
						/>
					</h1>
					<RichText
						tagName="p"
						value={ subhead }
						onChange={ ( v ) => setAttributes( { subhead: v } ) }
						placeholder={ __( 'Subhead…', 'chosen-theme' ) }
						style={ {
							fontStyle: 'italic',
							fontWeight: 300,
							fontSize: 18,
							margin: '0 0 24px',
							color: 'rgba(255,255,255,0.85)',
						} }
					/>
					<div
						style={ {
							display: 'inline-flex',
							gap: 18,
							marginBottom: 24,
							alignItems: 'stretch',
						} }
					>
						<div
							style={ {
								background: '#EDA90C',
								color: '#0B0A55',
								padding: '12px 18px',
								borderRadius: 10,
								fontFamily: 'Anton, sans-serif',
								lineHeight: 0.95,
								textAlign: 'left',
							} }
						>
							<div style={ { fontSize: 40 } }>{ dateRange }</div>
							<div style={ { fontSize: 14, letterSpacing: '0.04em' } }>{ dateMonth }</div>
						</div>
						<div
							style={ {
								display: 'flex',
								flexDirection: 'column',
								justifyContent: 'center',
								textAlign: 'left',
							} }
						>
							<div
								style={ {
									fontSize: 10,
									fontWeight: 700,
									letterSpacing: '0.18em',
									color: '#EDA90C',
									textTransform: 'uppercase',
								} }
							>
								{ __( 'Venue', 'chosen-theme' ) }
							</div>
							<div style={ { fontSize: 16, fontWeight: 700 } }>{ venueName }</div>
							<div style={ { fontSize: 12, color: 'rgba(255,255,255,0.7)' } }>{ venueCity }</div>
						</div>
					</div>
					<div>
						<RichText
							tagName="span"
							value={ ctaLabel }
							onChange={ ( v ) => setAttributes( { ctaLabel: v } ) }
							placeholder="Register"
							style={ {
								display: 'inline-block',
								background: '#EDA90C',
								color: '#0B0A55',
								padding: '12px 22px',
								borderRadius: 9999,
								fontWeight: 700,
								fontSize: 12,
								letterSpacing: '0.10em',
								textTransform: 'uppercase',
							} }
						/>
					</div>
				</div>
				{ ! backgroundImage?.url && (
					<div
						style={ {
							position: 'absolute',
							bottom: 16,
							right: 16,
							fontSize: 11,
							color: 'rgba(255,255,255,0.5)',
							fontStyle: 'italic',
						} }
					>
						{ __( 'No photo set — pick one in Inspector → Background photo.', 'chosen-theme' ) }
					</div>
				) }
			</div>
		</>
	);
}
