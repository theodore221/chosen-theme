import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, body, scripture, cite } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-vision-editor bg-chosen-navy text-white p-10 rounded-md',
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
			<RichText
				tagName="h2"
				value={ headline }
				onChange={ ( v ) => setAttributes( { headline: v } ) }
				placeholder={ __( 'Vision headline…', 'chosen-theme' ) }
				style={ {
					fontFamily: 'Anton, sans-serif',
					fontSize: 56,
					lineHeight: 1.05,
					margin: '12px 0 0',
					textTransform: 'uppercase',
				} }
			/>
			{ ( body || true ) && (
				<RichText
					tagName="p"
					value={ body }
					onChange={ ( v ) => setAttributes( { body: v } ) }
					placeholder={ __( 'Optional supporting paragraph…', 'chosen-theme' ) }
					style={ {
						fontSize: 17,
						lineHeight: 1.55,
						color: 'rgba(255,255,255,0.85)',
						maxWidth: 600,
						margin: '20px 0 0',
					} }
				/>
			) }
			<div
				style={ {
					width: 48,
					height: 3,
					background: '#EDA90C',
					marginTop: 28,
				} }
			/>
			<RichText
				tagName="blockquote"
				value={ scripture }
				onChange={ ( v ) => setAttributes( { scripture: v } ) }
				placeholder={ __( 'Scripture verse…', 'chosen-theme' ) }
				style={ {
					fontFamily: 'Work Sans, sans-serif',
					fontStyle: 'italic',
					fontWeight: 300,
					fontSize: 22,
					lineHeight: 1.4,
					margin: '20px 0 0',
					maxWidth: 580,
				} }
			/>
			<RichText
				tagName="cite"
				value={ cite }
				onChange={ ( v ) => setAttributes( { cite: v } ) }
				placeholder={ __( 'Reference…', 'chosen-theme' ) }
				style={ {
					display: 'inline-block',
					fontStyle: 'normal',
					color: '#EDA90C',
					fontSize: 11,
					fontWeight: 700,
					letterSpacing: '0.18em',
					textTransform: 'uppercase',
					marginTop: 14,
				} }
			/>
		</div>
	);
}
