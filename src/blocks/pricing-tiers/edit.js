import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		headline,
		subhead,
		tiers,
		inclusionsHeading,
		inclusions,
		callouts,
		ctaLabel,
		background,
		earlyBirdEndsDate,
		earlyBirdLabel,
		earlyBirdSubLabel,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-pricing-tiers-editor bg-chosen-paper p-10 rounded-md',
	} );

	const updateTier = ( i, key, value ) => {
		const next = tiers.map( ( t, idx ) => ( idx === i ? { ...t, [ key ]: value } : t ) );
		setAttributes( { tiers: next } );
	};

	const updateInclusion = ( i, value ) =>
		setAttributes( { inclusions: inclusions.map( ( v, idx ) => ( idx === i ? value : v ) ) } );

	const removeInclusion = ( i ) =>
		setAttributes( { inclusions: inclusions.filter( ( _, idx ) => idx !== i ) } );

	const addInclusion = () => setAttributes( { inclusions: [ ...inclusions, 'New inclusion' ] } );

	const updateCallout = ( i, key, value ) => {
		const next = callouts.map( ( c, idx ) => ( idx === i ? { ...c, [ key ]: value } : c ) );
		setAttributes( { callouts: next } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Section', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Paper', value: 'paper' },
							{ label: 'Cream', value: 'cream' },
							{ label: 'White', value: 'white' },
							{ label: 'Sky', value: 'sky' },
							{ label: 'Aqua', value: 'aqua' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
					<TextControl
						label={ __( 'CTA label', 'chosen-theme' ) }
						value={ ctaLabel }
						onChange={ ( v ) => setAttributes( { ctaLabel: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Early bird pricing', 'chosen-theme' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Early bird ends (YYYY-MM-DD)', 'chosen-theme' ) }
						value={ earlyBirdEndsDate }
						onChange={ ( v ) => setAttributes( { earlyBirdEndsDate: v } ) }
						help={ __( 'After this date, the standard price replaces the early bird price automatically.', 'chosen-theme' ) }
					/>
					<TextControl
						label={ __( 'Early bird label', 'chosen-theme' ) }
						value={ earlyBirdLabel }
						onChange={ ( v ) => setAttributes( { earlyBirdLabel: v } ) }
					/>
					<TextControl
						label={ __( 'Early bird sub-label', 'chosen-theme' ) }
						value={ earlyBirdSubLabel }
						onChange={ ( v ) => setAttributes( { earlyBirdSubLabel: v } ) }
						help={ __( 'Shown next to the chip in the section heading.', 'chosen-theme' ) }
					/>
				</PanelBody>
				{ tiers.map( ( tier, i ) => (
					<PanelBody key={ i } title={ tier.region || `Tier ${ i + 1 }` } initialOpen={ false }>
						<TextControl
							label={ __( 'Region', 'chosen-theme' ) }
							value={ tier.region }
							onChange={ ( v ) => updateTier( i, 'region', v ) }
						/>
						<TextControl
							label={ __( 'Early bird price', 'chosen-theme' ) }
							value={ tier.earlyBirdPrice || '' }
							onChange={ ( v ) => updateTier( i, 'earlyBirdPrice', v ) }
						/>
						<TextControl
							label={ __( 'Standard price', 'chosen-theme' ) }
							value={ tier.standardPrice || '' }
							onChange={ ( v ) => updateTier( i, 'standardPrice', v ) }
							help={ __( 'Shown after the early-bird cutoff.', 'chosen-theme' ) }
						/>
						<TextareaControl
							label={ __( 'Description', 'chosen-theme' ) }
							value={ tier.description }
							onChange={ ( v ) => updateTier( i, 'description', v ) }
							rows={ 2 }
						/>
					</PanelBody>
				) ) }
				<PanelBody title={ __( 'Inclusions', 'chosen-theme' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Heading', 'chosen-theme' ) }
						value={ inclusionsHeading }
						onChange={ ( v ) => setAttributes( { inclusionsHeading: v } ) }
					/>
					{ inclusions.map( ( inc, i ) => (
						<div key={ i } style={ { display: 'flex', gap: 4, marginBottom: 6 } }>
							<TextControl
								value={ inc }
								onChange={ ( v ) => updateInclusion( i, v ) }
								__nextHasNoMarginBottom
							/>
							<Button isDestructive variant="tertiary" onClick={ () => removeInclusion( i ) }>
								×
							</Button>
						</div>
					) ) }
					<Button variant="secondary" onClick={ addInclusion }>
						{ __( '+ Add inclusion', 'chosen-theme' ) }
					</Button>
				</PanelBody>
				{ callouts.map( ( c, i ) => (
					<PanelBody key={ i } title={ c.title || `Callout ${ i + 1 }` } initialOpen={ false }>
						<TextControl
							label={ __( 'Title', 'chosen-theme' ) }
							value={ c.title }
							onChange={ ( v ) => updateCallout( i, 'title', v ) }
						/>
						<TextareaControl
							label={ __( 'Body', 'chosen-theme' ) }
							value={ c.body }
							onChange={ ( v ) => updateCallout( i, 'body', v ) }
							rows={ 4 }
						/>
					</PanelBody>
				) ) }
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
					value={ headline }
					onChange={ ( v ) => setAttributes( { headline: v } ) }
					placeholder={ __( 'Headline…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Anton, sans-serif',
						fontSize: 44,
						lineHeight: 1.05,
						margin: '12px 0 16px',
						color: '#0B0A55',
						textTransform: 'uppercase',
					} }
				/>
				<RichText
					tagName="p"
					value={ subhead }
					onChange={ ( v ) => setAttributes( { subhead: v } ) }
					placeholder={ __( 'Subhead…', 'chosen-theme' ) }
					style={ {
						fontSize: 16,
						lineHeight: 1.5,
						color: '#3F3B33',
						maxWidth: 720,
						margin: '0 0 32px',
					} }
				/>

				<div
					style={ {
						display: 'grid',
						gridTemplateColumns: 'repeat(3, 1fr)',
						gap: 16,
						marginBottom: 24,
					} }
				>
					{ tiers.map( ( tier, i ) => {
						const ebPrice = tier.earlyBirdPrice || tier.price || '';
						const stdPrice = tier.standardPrice || '';
						return (
							<div
								key={ i }
								style={ {
									background: '#FFFFFF',
									borderRadius: 14,
									padding: 24,
									color: '#0B0A55',
									border: '1px solid rgba(11,10,85,0.10)',
									textAlign: 'left',
								} }
							>
								<div
									style={ {
										fontSize: 11,
										fontWeight: 700,
										letterSpacing: '0.14em',
										textTransform: 'uppercase',
										color: '#EDA90C',
									} }
								>
									{ tier.region }
								</div>
								{ ebPrice && stdPrice && (
									<div
										style={ {
											fontSize: 10,
											fontWeight: 700,
											letterSpacing: '0.16em',
											textTransform: 'uppercase',
											color: '#EDA90C',
											marginTop: 6,
										} }
									>
										{ '✦ ' + ( earlyBirdLabel || 'Early bird' ) }
									</div>
								) }
								<div
									style={ {
										fontFamily: 'Anton, sans-serif',
										fontSize: 56,
										lineHeight: 1,
										marginTop: 8,
									} }
								>
									{ ebPrice || stdPrice || '—' }
								</div>
								{ stdPrice && ebPrice && (
									<div style={ { fontSize: 11, marginTop: 8, color: 'rgba(11,10,85,0.55)' } }>
										{ __( 'Standard ', 'chosen-theme' ) + stdPrice + __( ' after ', 'chosen-theme' ) + ( earlyBirdEndsDate || '—' ) }
									</div>
								) }
								<div style={ { fontSize: 13, marginTop: 10, color: '#3F3B33' } }>
									{ tier.description }
								</div>
							</div>
						);
					} ) }
				</div>

				<div
					style={ {
						background: '#FFFFFF',
						borderRadius: 14,
						padding: 24,
						color: '#0B0A55',
						border: '1px solid rgba(11,10,85,0.10)',
						marginBottom: 24,
					} }
				>
					<div style={ { fontSize: 14, fontWeight: 700 } }>{ inclusionsHeading }</div>
					<ul style={ { fontSize: 14, marginTop: 10, paddingLeft: 0, listStyle: 'none' } }>
						{ inclusions.map( ( inc, i ) => (
							<li key={ i } style={ { marginBottom: 4 } }>
								✓ { inc }
							</li>
						) ) }
					</ul>
				</div>

				<div style={ { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 } }>
					{ callouts.map( ( c, i ) => (
						<div
							key={ i }
							style={ {
								background: [ '#FAE2A3', '#F4B7A0', '#C7DCE8' ][ i % 3 ],
								borderRadius: 14,
								padding: 18,
								color: '#0B0A55',
							} }
						>
							<div style={ { fontWeight: 700, fontSize: 14 } }>{ c.title }</div>
							<div style={ { fontSize: 13, marginTop: 6, lineHeight: 1.4 } }>
								{ c.body }
							</div>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
