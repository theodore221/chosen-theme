import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, subhead, milestones, background } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-timeline-strip-editor bg-chosen-navy text-white p-10 rounded-md',
	} );

	const updateMilestone = ( i, key, value ) => {
		const next = milestones.map( ( m, idx ) => ( idx === i ? { ...m, [ key ]: value } : m ) );
		setAttributes( { milestones: next } );
	};

	const removeMilestone = ( i ) =>
		setAttributes( { milestones: milestones.filter( ( _, idx ) => idx !== i ) } );

	const addMilestone = () =>
		setAttributes( {
			milestones: [
				...milestones,
				{ year: '2030', name: 'New milestone', description: '', current: false },
			],
		} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Section', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Navy', value: 'navy' },
							{ label: 'Royal Blue', value: 'royal' },
							{ label: 'Paper', value: 'paper' },
							{ label: 'Cream', value: 'cream' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				{ milestones.map( ( m, i ) => (
					<PanelBody
						key={ i }
						title={ ( m.year || '—' ) + ' · ' + ( m.name || `Milestone ${ i + 1 }` ) }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Year', 'chosen-theme' ) }
							value={ m.year }
							onChange={ ( v ) => updateMilestone( i, 'year', v ) }
						/>
						<TextControl
							label={ __( 'Name', 'chosen-theme' ) }
							value={ m.name }
							onChange={ ( v ) => updateMilestone( i, 'name', v ) }
						/>
						<TextareaControl
							label={ __( 'Description', 'chosen-theme' ) }
							value={ m.description }
							onChange={ ( v ) => updateMilestone( i, 'description', v ) }
							rows={ 3 }
						/>
						<ToggleControl
							label={ __( 'Highlight as current', 'chosen-theme' ) }
							checked={ !! m.current }
							onChange={ ( v ) => updateMilestone( i, 'current', v ) }
						/>
						<Button isDestructive variant="link" onClick={ () => removeMilestone( i ) }>
							{ __( 'Remove milestone', 'chosen-theme' ) }
						</Button>
					</PanelBody>
				) ) }
				<PanelBody title={ __( 'Add', 'chosen-theme' ) } initialOpen={ false }>
					<Button variant="primary" onClick={ addMilestone }>
						{ __( '+ Add milestone', 'chosen-theme' ) }
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
						fontSize: 38,
						lineHeight: 1.08,
						margin: '12px 0 16px',
						color: '#FFFFFF',
						textTransform: 'uppercase',
					} }
				/>
				<RichText
					tagName="p"
					value={ subhead }
					onChange={ ( v ) => setAttributes( { subhead: v } ) }
					placeholder={ __( 'Optional subhead…', 'chosen-theme' ) }
					style={ {
						fontSize: 16,
						lineHeight: 1.5,
						color: 'rgba(255,255,255,0.85)',
						maxWidth: 720,
						margin: '0 0 28px',
					} }
				/>
				<div
					style={ {
						display: 'grid',
						gridTemplateColumns: `repeat(${ milestones.length }, 1fr)`,
						gap: 12,
					} }
				>
					{ milestones.map( ( m, i ) => (
						<div
							key={ i }
							style={ {
								borderTop: '2px solid ' + ( m.current ? '#EDA90C' : 'rgba(255,255,255,0.25)' ),
								paddingTop: 16,
								color: '#FFFFFF',
							} }
						>
							<div
								style={ {
									fontFamily: 'Anton, sans-serif',
									fontSize: 36,
									letterSpacing: '0.02em',
									color: m.current ? '#EDA90C' : '#FFFFFF',
								} }
							>
								{ m.year }
							</div>
							<div style={ { fontSize: 13, fontWeight: 700, marginTop: 4 } }>{ m.name }</div>
							<div
								style={ {
									fontSize: 12,
									lineHeight: 1.4,
									marginTop: 6,
									opacity: 0.8,
								} }
							>
								{ m.description }
							</div>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
