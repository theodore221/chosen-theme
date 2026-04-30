---
description: Scaffold a new custom Gutenberg block for chosen-theme
---

Create a new WordPress block `chosen/$ARGUMENTS` inside `src/blocks/$ARGUMENTS/`.

Create these three files with the correct namespace, escaping, and Tailwind class structure:

**`src/blocks/$ARGUMENTS/block.json`:**
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "chosen/$ARGUMENTS",
  "title": "$ARGUMENTS",
  "category": "chosen",
  "description": "",
  "supports": { "html": false },
  "attributes": {},
  "editorScript": "file:./index.js",
  "render": "file:./render.php"
}
```

**`src/blocks/$ARGUMENTS/edit.js`:**
```js
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
  const blockProps = useBlockProps();
  return (
    <>
      <InspectorControls>
        <PanelBody title={ __( 'Settings', 'chosen-theme' ) }>
          {/* Add InspectorControls here */}
        </PanelBody>
      </InspectorControls>
      <div { ...blockProps }>
        <p>{ __( 'chosen/$ARGUMENTS — editor preview', 'chosen-theme' ) }</p>
      </div>
    </>
  );
}
```

**`src/blocks/$ARGUMENTS/render.php`:**
```php
<?php
/**
 * chosen/$ARGUMENTS block — server-side render.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    InnerBlocks content.
 * @param WP_Block $block      Block instance.
 */
?>
<section <?php echo get_block_wrapper_attributes( [ 'class' => 'chosen-$ARGUMENTS' ] ); ?>>
  <!-- Block output goes here. Use Tailwind utilities. Escape all output. -->
</section>
```

Then register the block in `inc/block-registration.php` by adding:
```php
register_block_type( __DIR__ . '/../src/blocks/$ARGUMENTS' );
```

Finally run:
```bash
npm run build
```

Reference `design-system/preview/` for visual design patterns and `design-system/colors_and_type.css` for CSS custom properties.
