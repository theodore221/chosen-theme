import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	Button,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { items, speed, background } = attributes;

	const blockProps = useBlockProps( {
		className:
			'chosen-marquee-editor flex items-center gap-6 overflow-hidden bg-chosen-navy text-white px-4 py-3',
	} );

	const updateItem = ( index, key, value ) => {
		const next = items.map( ( item, i ) =>
			i === index ? { ...item, [ key ]: value } : item
		);
		setAttributes( { items: next } );
	};

	const addItem = () =>
		setAttributes( {
			items: [ ...items, { text: 'NEW ITEM', color: 'gold' } ],
		} );

	const removeItem = ( index ) =>
		setAttributes( { items: items.filter( ( _, i ) => i !== index ) } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Items', 'chosen-theme' ) } initialOpen={ true }>
					{ items.map( ( item, index ) => (
						<div
							key={ index }
							style={ {
								marginBottom: 12,
								padding: 8,
								background: '#f7f7f7',
								borderRadius: 4,
							} }
						>
							<TextControl
								label={ __( 'Text', 'chosen-theme' ) }
								value={ item.text }
								onChange={ ( v ) => updateItem( index, 'text', v ) }
							/>
							<ToggleControl
								label={ __( 'Render in gold', 'chosen-theme' ) }
								checked={ item.color === 'gold' }
								onChange={ ( v ) =>
									updateItem( index, 'color', v ? 'gold' : 'white' )
								}
							/>
							<Button
								variant="link"
								isDestructive
								onClick={ () => removeItem( index ) }
							>
								{ __( 'Remove item', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button variant="primary" onClick={ addItem }>
						{ __( '+ Add item', 'chosen-theme' ) }
					</Button>
				</PanelBody>
				<PanelBody title={ __( 'Background', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Surface colour', 'chosen-theme' ) }
						value={ background || 'navy' }
						options={ [
							{ label: 'Navy (default)', value: 'navy' },
							{ label: 'Warm paper', value: 'paper' },
							{ label: 'Royal blue', value: 'royal' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
						help={ __(
							'Use to break up successive navy sections. Default white items render as navy on paper backgrounds.',
							'chosen-theme'
						) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Motion', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Scroll speed', 'chosen-theme' ) }
						value={ speed }
						options={ [
							{ label: 'Slow (60s)', value: 'slow' },
							{ label: 'Medium (40s)', value: 'medium' },
							{ label: 'Fast (25s)', value: 'fast' },
						] }
						onChange={ ( v ) => setAttributes( { speed: v } ) }
						help={ __(
							'Animation pauses on hover, and stops entirely when the visitor has reduced-motion preference.',
							'chosen-theme'
						) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<span style={ { opacity: 0.6, fontSize: 11, fontFamily: 'monospace' } }>
					marquee →
				</span>
				{ items.map( ( item, i ) => (
					<span
						key={ i }
						style={ {
							fontFamily: 'Anton, sans-serif',
							fontSize: 22,
							color: item.color === 'gold' ? '#EDA90C' : '#FFFFFF',
							letterSpacing: '0.04em',
							textTransform: 'uppercase',
							whiteSpace: 'nowrap',
						} }
					>
						{ item.text }
					</span>
				) ) }
			</div>
		</>
	);
}
