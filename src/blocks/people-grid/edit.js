import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const PHOTO_OPTIONS = [
	{ label: 'dsc00143 — Joy/UPPERROOM', value: 'dsc00143' },
	{ label: 'dsc00155 — Anointing', value: 'dsc00155' },
	{ label: 'dsc00238 — Corridor', value: 'dsc00238' },
	{ label: 'dsc00424 — Adoration', value: 'dsc00424' },
	{ label: 'dsc00635 — Friendship', value: 'dsc00635' },
	{ label: 'dsc09433 — Energy', value: 'dsc09433' },
	{ label: 'dsc09854 — Brotherhood', value: 'dsc09854' },
	{ label: 'img_4837 — Group', value: 'img_4837' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, heading, members, background } = attributes;

	const isNavy = background === 'navy';
	const bgColor = isNavy ? '#0B0A55' : background === 'white' ? '#FFFFFF' : '#FAF8F3';
	const textColor = isNavy ? '#FFFFFF' : '#14130F';

	const blockProps = useBlockProps( {
		className: 'chosen-people-grid-editor p-10',
		style: { backgroundColor: bgColor, color: textColor },
	} );

	const updateMember = ( i, key, value ) => {
		const next = [ ...members ];
		next[ i ] = { ...next[ i ], [ key ]: value };
		setAttributes( { members: next } );
	};

	const addMember = () => {
		setAttributes( {
			members: [ ...members, { name: 'TBA', role: 'Role', photoStem: 'dsc09854', photoAlt: 'Placeholder portrait' } ],
		} );
	};

	const removeMember = ( i ) => {
		setAttributes( { members: members.filter( ( _, idx ) => idx !== i ) } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Background', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Warm paper', value: 'paper' },
							{ label: 'White', value: 'white' },
							{ label: 'Navy (dark)', value: 'navy' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Members', 'chosen-theme' ) } initialOpen={ true }>
					{ members.map( ( m, i ) => (
						<div key={ i } style={ { borderTop: '1px solid #ddd', paddingTop: 12, marginTop: 12 } }>
							<TextControl
								label={ `Name #${ i + 1 }` }
								value={ m.name }
								onChange={ ( v ) => updateMember( i, 'name', v ) }
							/>
							<TextControl
								label="Role"
								value={ m.role }
								onChange={ ( v ) => updateMember( i, 'role', v ) }
							/>
							<SelectControl
								label="Photo"
								value={ m.photoStem }
								options={ PHOTO_OPTIONS }
								onChange={ ( v ) => updateMember( i, 'photoStem', v ) }
							/>
							<TextControl
								label="Alt text"
								value={ m.photoAlt }
								onChange={ ( v ) => updateMember( i, 'photoAlt', v ) }
							/>
							<Button isDestructive onClick={ () => removeMember( i ) }>
								{ __( 'Remove', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button isPrimary style={ { marginTop: 16 } } onClick={ addMember }>
						{ __( 'Add member', 'chosen-theme' ) }
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
					value={ heading }
					onChange={ ( v ) => setAttributes( { heading: v } ) }
					placeholder={ __( 'Heading…', 'chosen-theme' ) }
					style={ {
						fontFamily: 'Anton, sans-serif',
						fontSize: 'clamp(48px, 9vw, 144px)',
						lineHeight: 0.92,
						textTransform: 'uppercase',
						margin: '20px 0 32px',
					} }
				/>
				<div style={ { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 24 } }>
					{ members.map( ( m, i ) => (
						<div key={ i } style={ { textAlign: 'center' } }>
							<div style={ {
								width: 140,
								height: 140,
								borderRadius: '9999px',
								background: '#0B0A55',
								margin: '0 auto 12px',
								color: '#fff',
								display: 'flex',
								alignItems: 'center',
								justifyContent: 'center',
								fontFamily: 'Anton, sans-serif',
								fontSize: 32,
							} }>{ m.name?.charAt( 0 ) || '·' }</div>
							<p style={ { margin: 0, fontWeight: 600 }}>{ m.name }</p>
							<p style={ { margin: '4px 0 0', fontSize: 11, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#EDA90C' } }>{ m.role }</p>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
