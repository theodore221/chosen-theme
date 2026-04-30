import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { headline, subhead, ctaLabel, eyebrow, scripture, cite } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-cta-banner-editor bg-chosen-navy text-white p-10 rounded-md',
	} );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="h2"
				value={ headline }
				onChange={ ( v ) => setAttributes( { headline: v } ) }
				placeholder={ __( 'Headline…', 'chosen-theme' ) }
				style={ {
					fontFamily: 'Anton, sans-serif',
					fontSize: 64,
					lineHeight: 1.05,
					margin: 0,
					textTransform: 'uppercase',
				} }
			/>
			<RichText
				tagName="p"
				value={ subhead }
				onChange={ ( v ) => setAttributes( { subhead: v } ) }
				placeholder={ __( 'Subhead…', 'chosen-theme' ) }
				style={ {
					fontStyle: 'italic',
					fontWeight: 300,
					fontSize: 18,
					margin: '14px 0 0',
					color: 'rgba(255,255,255,0.85)',
					maxWidth: 480,
				} }
			/>
			<RichText
				tagName="span"
				value={ ctaLabel }
				onChange={ ( v ) => setAttributes( { ctaLabel: v } ) }
				placeholder="Register"
				style={ {
					display: 'inline-block',
					marginTop: 24,
					background: '#EDA90C',
					color: '#0B0A55',
					padding: '12px 24px',
					borderRadius: 9999,
					fontWeight: 700,
					fontSize: 12,
					letterSpacing: '0.10em',
					textTransform: 'uppercase',
				} }
			/>
			<div
				style={ {
					marginTop: 40,
					paddingTop: 24,
					borderTop: '1px solid rgba(255,255,255,0.12)',
					maxWidth: 580,
				} }
			>
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
					value={ scripture }
					onChange={ ( v ) => setAttributes( { scripture: v } ) }
					placeholder={ __( 'Scripture…', 'chosen-theme' ) }
					style={ {
						fontStyle: 'italic',
						fontWeight: 300,
						fontSize: 18,
						lineHeight: 1.45,
						margin: '8px 0 0',
					} }
				/>
				<div
					style={ {
						width: 48,
						height: 3,
						background: '#EDA90C',
						marginTop: 14,
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
		</div>
	);
}
