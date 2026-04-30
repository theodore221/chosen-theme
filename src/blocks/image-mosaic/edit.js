import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	Button,
	TextControl,
	Notice,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { images, layout } = attributes;

	const blockProps = useBlockProps( {
		className: 'chosen-image-mosaic-editor p-6',
	} );

	const updateImage = ( index, key, value ) => {
		const next = images.map( ( img, i ) =>
			i === index ? { ...img, [ key ]: value } : img
		);
		setAttributes( { images: next } );
	};

	const removeImage = ( index ) =>
		setAttributes( { images: images.filter( ( _, i ) => i !== index ) } );

	const onSelectImage = ( index, media ) =>
		updateImage( index, 'url', media.url ) ||
		setAttributes( {
			images: images.map( ( img, i ) =>
				i === index
					? { id: media.id, url: media.url, alt: media.alt || img.alt }
					: img
			),
		} );

	const addImage = () =>
		setAttributes( {
			images: [ ...images, { id: 0, url: '', alt: '' } ],
		} );

	const missingAlt = images.some(
		( img ) => img.url && ! ( img.alt || '' ).trim()
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'chosen-theme' ) }>
					<SelectControl
						label={ __( 'Mosaic style', 'chosen-theme' ) }
						value={ layout }
						options={ [
							{ label: 'Bento (asymmetric)', value: 'bento' },
							{ label: 'Even grid', value: 'even' },
						] }
						onChange={ ( v ) => setAttributes( { layout: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Images', 'chosen-theme' ) } initialOpen={ true }>
					{ missingAlt && (
						<Notice status="warning" isDismissible={ false }>
							{ __( 'One or more images are missing alt text. Required for accessibility before publishing.', 'chosen-theme' ) }
						</Notice>
					) }
					{ images.map( ( img, index ) => (
						<div
							key={ index }
							style={ {
								marginBottom: 16,
								padding: 10,
								background: '#f7f7f7',
								borderRadius: 4,
							} }
						>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) => onSelectImage( index, media ) }
									allowedTypes={ [ 'image' ] }
									value={ img.id }
									render={ ( { open } ) => (
										<Button variant="secondary" onClick={ open }>
											{ img.url
												? __( 'Replace image', 'chosen-theme' )
												: __( 'Select image', 'chosen-theme' ) }
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ img.url && (
								<>
									<div
										style={ {
											marginTop: 8,
											height: 80,
											background: `url(${ img.url }) center/cover no-repeat`,
											borderRadius: 4,
										} }
									/>
									<TextControl
										label={ __( 'Alt text (required)', 'chosen-theme' ) }
										value={ img.alt || '' }
										onChange={ ( v ) => updateImage( index, 'alt', v ) }
									/>
								</>
							) }
							<Button
								variant="link"
								isDestructive
								onClick={ () => removeImage( index ) }
							>
								{ __( 'Remove image', 'chosen-theme' ) }
							</Button>
						</div>
					) ) }
					<Button variant="primary" onClick={ addImage } disabled={ images.length >= 9 }>
						{ __( '+ Add image', 'chosen-theme' ) }
					</Button>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div
					style={ {
						display: 'grid',
						gridTemplateColumns:
							layout === 'bento'
								? 'repeat(4, 1fr)'
								: 'repeat(3, 1fr)',
						gridAutoRows: layout === 'bento' ? '120px' : '180px',
						gap: 4,
					} }
				>
					{ images.map( ( img, i ) => (
						<div
							key={ i }
							style={ {
								gridColumn:
									layout === 'bento' && i === 0
										? 'span 2'
										: layout === 'bento' && i === 3
										? 'span 2'
										: 'span 1',
								gridRow:
									layout === 'bento' && ( i === 0 || i === 3 )
										? 'span 2'
										: 'span 1',
								background: img.url ? `url(${ img.url }) center/cover no-repeat` : '#E5DFD0',
							} }
						/>
					) ) }
				</div>
				{ images.length === 0 && (
					<p style={ { textAlign: 'center', color: '#807A6A', marginTop: 12 } }>
						{ __( 'Add images via Inspector → Images.', 'chosen-theme' ) }
					</p>
				) }
			</div>
		</>
	);
}
