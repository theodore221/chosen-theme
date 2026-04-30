import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { stats, background } = attributes;

	const isPaper = background === 'paper';

	const blockProps = useBlockProps( {
		className: `chosen-stat-strip-editor p-10 rounded-md ${
			isPaper ? 'bg-chosen-paper text-chosen-navy' : 'bg-chosen-royal text-white'
		}`,
		style: {
			backgroundColor: isPaper ? '#FAF8F3' : '#4071AC',
			color: isPaper ? '#0B0A55' : '#FFFFFF',
		},
	} );

	const updateStat = ( index, key, value ) => {
		const next = stats.map( ( s, i ) =>
			i === index ? { ...s, [ key ]: value } : s
		);
		setAttributes( { stats: next } );
	};

	const addStat = () =>
		setAttributes( {
			stats: [ ...stats, { value: 0, prefix: '', suffix: '', label: 'New stat' } ],
		} );

	const removeStat = ( index ) =>
		setAttributes( { stats: stats.filter( ( _, i ) => i !== index ) } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Background', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Section background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Warm paper', value: 'paper' },
							{ label: 'Royal blue', value: 'royal' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Stats', 'chosen-theme' ) }>
					{ stats.map( ( stat, index ) => (
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
								label={ __( 'Number', 'chosen-theme' ) }
								type="number"
								value={ stat.value }
								onChange={ ( v ) =>
									updateStat( index, 'value', parseInt( v, 10 ) || 0 )
								}
							/>
							<TextControl
								label={ __( 'Prefix (optional)', 'chosen-theme' ) }
								value={ stat.prefix || '' }
								onChange={ ( v ) => updateStat( index, 'prefix', v ) }
								help={ __( 'e.g. "$"', 'chosen-theme' ) }
							/>
							<TextControl
								label={ __( 'Suffix (optional)', 'chosen-theme' ) }
								value={ stat.suffix || '' }
								onChange={ ( v ) => updateStat( index, 'suffix', v ) }
								help={ __( 'e.g. "+", "%"', 'chosen-theme' ) }
							/>
							<TextControl
								label={ __( 'Label', 'chosen-theme' ) }
								value={ stat.label }
								onChange={ ( v ) => updateStat( index, 'label', v ) }
							/>
							<Button
								variant="link"
								isDestructive
								onClick={ () => removeStat( index ) }
							>
								{ __( 'Remove stat', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button variant="primary" onClick={ addStat } disabled={ stats.length >= 6 }>
						{ __( '+ Add stat', 'chosen-theme' ) }
					</Button>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div
					style={ {
						display: 'grid',
						gridTemplateColumns: `repeat(${ Math.min( stats.length, 4 ) }, 1fr)`,
						gap: 24,
					} }
				>
					{ stats.map( ( stat, i ) => (
						<div key={ i } style={ { textAlign: 'left' } }>
							<div
								style={ {
									fontFamily: 'Anton, sans-serif',
									fontSize: 56,
									lineHeight: 1,
								} }
							>
								{ stat.prefix || '' }
								{ stat.value }
								{ stat.suffix || '' }
							</div>
							<div
								style={ {
									fontSize: 11,
									fontWeight: 700,
									letterSpacing: '0.18em',
									textTransform: 'uppercase',
									color: '#EDA90C',
									marginTop: 6,
								} }
							>
								{ stat.label }
							</div>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
