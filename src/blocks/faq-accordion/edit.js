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
	const { eyebrow, headline, items, background } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-faq-accordion-editor bg-chosen-paper p-10 rounded-md',
	} );

	const updateItem = ( i, key, value ) => {
		const next = items.map( ( item, idx ) => ( idx === i ? { ...item, [ key ]: value } : item ) );
		setAttributes( { items: next } );
	};

	const addItem = () =>
		setAttributes( {
			items: [ ...items, { question: 'New question?', answer: 'Answer…' } ],
		} );

	const removeItem = ( i ) => setAttributes( { items: items.filter( ( _, idx ) => idx !== i ) } );

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
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Questions', 'chosen-theme' ) } initialOpen={ true }>
					{ items.map( ( item, i ) => (
						<div
							key={ i }
							style={ {
								marginBottom: 12,
								padding: 10,
								background: '#f7f7f7',
								borderRadius: 4,
							} }
						>
							<TextControl
								label={ __( 'Question', 'chosen-theme' ) }
								value={ item.question }
								onChange={ ( v ) => updateItem( i, 'question', v ) }
							/>
							<TextareaControl
								label={ __( 'Answer', 'chosen-theme' ) }
								value={ item.answer }
								onChange={ ( v ) => updateItem( i, 'answer', v ) }
								rows={ 4 }
							/>
							<Button isDestructive variant="link" onClick={ () => removeItem( i ) }>
								{ __( 'Remove question', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button variant="primary" onClick={ addItem } disabled={ items.length >= 30 }>
						{ __( '+ Add question', 'chosen-theme' ) }
					</Button>
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
					tagName="h2"
					value={ headline }
					onChange={ ( v ) => setAttributes( { headline: v } ) }
					placeholder={ __( 'Headline…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Anton, sans-serif',
						fontSize: 44,
						lineHeight: 1.05,
						margin: '12px 0 24px',
						color: '#0B0A55',
						textTransform: 'uppercase',
					} }
				/>
				<div>
					{ items.map( ( item, i ) => (
						<details
							key={ i }
							style={ {
								background: '#FFFFFF',
								borderRadius: 10,
								padding: '14px 18px',
								marginBottom: 8,
								border: '1px solid rgba(11,10,85,0.10)',
							} }
						>
							<summary
								style={ {
									cursor: 'pointer',
									fontWeight: 600,
									color: '#0B0A55',
									fontSize: 15,
								} }
							>
								{ item.question }
							</summary>
							<p style={ { fontSize: 14, lineHeight: 1.5, marginTop: 10, color: '#3F3B33' } }>
								{ item.answer }
							</p>
						</details>
					) ) }
				</div>
			</div>
		</>
	);
}
