import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, tiles } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-expect-tile-grid-editor bg-chosen-paper p-10 rounded-md',
	} );

	const updateTile = ( index, key, value ) => {
		const next = tiles.map( ( t, i ) =>
			i === index ? { ...t, [ key ]: value } : t
		);
		setAttributes( { tiles: next } );
	};

	const addTile = () =>
		setAttributes( {
			tiles: [ ...tiles, { title: 'New tile', description: 'Description…' } ],
		} );

	const removeTile = ( index ) =>
		setAttributes( { tiles: tiles.filter( ( _, i ) => i !== index ) } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Tiles', 'chosen-theme' ) } initialOpen={ true }>
					{ tiles.map( ( tile, index ) => (
						<div
							key={ index }
							style={ {
								marginBottom: 16,
								padding: 10,
								background: '#f7f7f7',
								borderRadius: 4,
							} }
						>
							<TextControl
								label={ __( 'Title', 'chosen-theme' ) }
								value={ tile.title }
								onChange={ ( v ) => updateTile( index, 'title', v ) }
							/>
							<TextareaControl
								label={ __( 'Description', 'chosen-theme' ) }
								value={ tile.description }
								onChange={ ( v ) => updateTile( index, 'description', v ) }
								rows={ 3 }
							/>
							<Button
								variant="link"
								isDestructive
								onClick={ () => removeTile( index ) }
							>
								{ __( 'Remove tile', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button variant="primary" onClick={ addTile } disabled={ tiles.length >= 12 }>
						{ __( '+ Add tile', 'chosen-theme' ) }
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
					placeholder={ __( 'Section headline…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Anton, sans-serif',
						fontSize: 44,
						lineHeight: 1.05,
						margin: '12px 0 28px',
						color: '#0B0A55',
						textTransform: 'uppercase',
					} }
				/>
				<div
					style={ {
						display: 'grid',
						gridTemplateColumns: 'repeat(4, 1fr)',
						gap: 16,
					} }
				>
					{ tiles.map( ( tile, i ) => (
						<div
							key={ i }
							style={ {
								background: '#FFFFFF',
								border: '1px solid rgba(11,10,85,0.12)',
								borderRadius: 8,
								padding: 16,
								color: '#0B0A55',
							} }
						>
							<div style={ { fontSize: 16, fontWeight: 700 } }>{ tile.title }</div>
							<div
								style={ {
									fontSize: 12,
									color: '#3F3B33',
									marginTop: 6,
									lineHeight: 1.4,
								} }
							>
								{ tile.description }
							</div>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
