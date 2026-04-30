import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, stats, background } = attributes;

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
				<RichText
					tagName="p"
					value={ eyebrow }
					onChange={ ( v ) => setAttributes( { eyebrow: v } ) }
					placeholder={ __( 'Optional eyebrow…', 'chosen-theme' ) }
					style={ {
						color: '#EDA90C',
						fontSize: 11,
						fontWeight: 700,
						letterSpacing: '0.18em',
						textTransform: 'uppercase',
						margin: '0 0 24px',
					} }
				/>
				<div
					style={ {
						display: 'grid',
						gridTemplateColumns: 'repeat(4, 1fr)',
						gap: 24,
						alignItems: 'flex-end',
					} }
				>
					{ stats.map( ( stat, i ) => {
						const featured = i === 0;
						return (
							<div
								key={ i }
								style={ {
									textAlign: 'left',
									gridColumn: featured ? 'span 2' : 'span 1',
								} }
							>
								<div
									style={ {
										fontFamily: 'Anton, sans-serif',
										fontSize: featured ? 96 : 48,
										lineHeight: 0.92,
										letterSpacing: '-0.025em',
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
										color: isPaper ? '#EDA90C' : 'rgba(255,255,255,0.85)',
										marginTop: 8,
									} }
								>
									{ stat.label }
								</div>
							</div>
						);
					} ) }
				</div>
			</div>
		</>
	);
}
