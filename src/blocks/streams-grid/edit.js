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
	const { eyebrow, headline, subhead, streams, background } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-streams-grid-editor bg-chosen-cream p-10 rounded-md',
	} );

	const updateStream = ( index, key, value ) => {
		const next = streams.map( ( s, i ) =>
			i === index ? { ...s, [ key ]: value } : s
		);
		setAttributes( { streams: next } );
	};

	const updateTopic = ( streamIndex, topicIndex, value ) => {
		const stream = streams[ streamIndex ];
		const nextTopics = stream.topics.map( ( t, i ) => ( i === topicIndex ? value : t ) );
		updateStream( streamIndex, 'topics', nextTopics );
	};

	const addTopic = ( streamIndex ) => {
		const stream = streams[ streamIndex ];
		updateStream( streamIndex, 'topics', [ ...stream.topics, 'New topic' ] );
	};

	const removeTopic = ( streamIndex, topicIndex ) => {
		const stream = streams[ streamIndex ];
		updateStream(
			streamIndex,
			'topics',
			stream.topics.filter( ( _, i ) => i !== topicIndex )
		);
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Section', 'chosen-theme' ) } initialOpen={ true }>
					<SelectControl
						label={ __( 'Background', 'chosen-theme' ) }
						value={ background }
						options={ [
							{ label: 'Cream', value: 'cream' },
							{ label: 'Paper', value: 'paper' },
							{ label: 'White', value: 'white' },
							{ label: 'Sage', value: 'sage' },
							{ label: 'Sky', value: 'sky' },
							{ label: 'Sun', value: 'sun' },
							{ label: 'Aqua', value: 'aqua' },
						] }
						onChange={ ( v ) => setAttributes( { background: v } ) }
					/>
				</PanelBody>
				{ streams.map( ( stream, i ) => (
					<PanelBody
						key={ i }
						title={ __( 'Stream — ', 'chosen-theme' ) + ( stream.name || `${ i + 1 }` ) }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Name', 'chosen-theme' ) }
							value={ stream.name }
							onChange={ ( v ) => updateStream( i, 'name', v ) }
						/>
						<TextControl
							label={ __( 'Age band', 'chosen-theme' ) }
							value={ stream.ageBand }
							onChange={ ( v ) => updateStream( i, 'ageBand', v ) }
						/>
						<TextareaControl
							label={ __( 'Intro', 'chosen-theme' ) }
							value={ stream.intro }
							onChange={ ( v ) => updateStream( i, 'intro', v ) }
							rows={ 4 }
						/>
						<p style={ { fontSize: 11, fontWeight: 600, marginTop: 12 } }>
							{ __( 'Topics', 'chosen-theme' ) }
						</p>
						{ stream.topics.map( ( topic, ti ) => (
							<div key={ ti } style={ { display: 'flex', gap: 4, marginBottom: 6 } }>
								<TextControl
									value={ topic }
									onChange={ ( v ) => updateTopic( i, ti, v ) }
									__nextHasNoMarginBottom
								/>
								<Button
									isDestructive
									variant="tertiary"
									onClick={ () => removeTopic( i, ti ) }
									aria-label={ __( 'Remove topic', 'chosen-theme' ) }
								>
									×
								</Button>
							</div>
						) ) }
						<Button variant="secondary" onClick={ () => addTopic( i ) }>
							{ __( '+ Add topic', 'chosen-theme' ) }
						</Button>
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
				<div style={ { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 16 } }>
					{ streams.map( ( stream, i ) => (
						<div
							key={ i }
							style={ {
								background: '#FFFFFF',
								borderRadius: 14,
								padding: 24,
								color: '#0B0A55',
								border: '1px solid rgba(11,10,85,0.10)',
							} }
						>
							<div
								style={ {
									fontFamily: 'Anton, sans-serif',
									fontSize: 28,
									textTransform: 'uppercase',
									lineHeight: 1.05,
								} }
							>
								{ stream.name || '—' }
							</div>
							<div
								style={ {
									fontSize: 11,
									fontWeight: 700,
									letterSpacing: '0.14em',
									textTransform: 'uppercase',
									color: '#EDA90C',
									marginTop: 6,
								} }
							>
								{ stream.ageBand }
							</div>
							<p style={ { fontSize: 14, lineHeight: 1.5, marginTop: 14 } }>
								{ stream.intro }
							</p>
							<ul style={ { fontSize: 13, marginTop: 14, paddingLeft: 16 } }>
								{ stream.topics.map( ( t, ti ) => (
									<li key={ ti } style={ { marginBottom: 6 } }>
										{ t }
									</li>
								) ) }
							</ul>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
