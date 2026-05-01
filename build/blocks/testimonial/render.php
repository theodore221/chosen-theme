<?php
/**
 * chosen/testimonial block — server-side render.
 *
 * Pull-quote testimonial. Oversized typographic quote-mark ornament (8% gold,
 * absolute-positioned) + italic Work Sans quote + small circular photo +
 * name + ALL CAPS age/city tag. Enters via .chosen-fade-up.
 *
 * @param array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$quote      = isset( $attributes['quote'] )     ? (string) $attributes['quote']     : '';
$name       = isset( $attributes['name'] )      ? (string) $attributes['name']      : '';
$age_city   = isset( $attributes['ageCity'] )   ? (string) $attributes['ageCity']   : '';
$photo_stem = isset( $attributes['photoStem'] ) ? preg_replace( '/[^a-z0-9_-]/', '', (string) $attributes['photoStem'] ) : 'dsc00635';
$bg         = isset( $attributes['background'] ) && in_array( $attributes['background'], [ 'paper', 'white', 'navy', 'cream', 'sage', 'sky', 'sun', 'coral' ], true )
	? $attributes['background']
	: 'paper';

if ( '' === trim( $quote ) ) {
	return;
}

$bg_class = [
	'paper' => 'bg-chosen-paper',
	'white' => 'bg-white',
	'navy'  => 'bg-chosen-navy text-white',
	'cream' => 'bg-chosen-cream',
	'sage'  => 'bg-chosen-sage',
	'sky'   => 'bg-chosen-sky',
	'sun'   => 'bg-chosen-sun',
	'coral' => 'bg-chosen-coral',
][ $bg ];

$is_navy = 'navy' === $bg;
$theme_uri = get_theme_file_uri();
$photo_base = $theme_uri . '/assets/img/photos-real/' . $photo_stem;

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'chosen-testimonial chosen-fade-up ' . $bg_class . ' my-12 mx-auto rounded-md max-w-content',
] );
?>
<figure <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<span class="chosen-testimonial__mark" aria-hidden="true">&ldquo;</span>
	<blockquote class="chosen-testimonial__quote relative <?php echo $is_navy ? 'text-white' : 'text-chosen-navy'; ?>">
		<?php echo esc_html( $quote ); ?>
	</blockquote>
	<figcaption class="chosen-testimonial__cite">
		<div class="chosen-testimonial__photo">
			<picture>
				<source type="image/webp" srcset="<?php echo esc_url( $photo_base . '-800.webp' ); ?>" />
				<img
					src="<?php echo esc_url( $photo_base . '-800.jpg' ); ?>"
					alt=""
					loading="lazy"
					decoding="async"
					width="56"
					height="56"
				/>
			</picture>
		</div>
		<div>
			<p class="chosen-testimonial__name <?php echo $is_navy ? 'text-white' : 'text-chosen-navy'; ?>">
				<?php echo esc_html( $name ); ?>
			</p>
			<?php if ( $age_city ) : ?>
				<p class="chosen-testimonial__where">
					<?php echo esc_html( $age_city ); ?>
				</p>
			<?php endif; ?>
		</div>
	</figcaption>
</figure>
