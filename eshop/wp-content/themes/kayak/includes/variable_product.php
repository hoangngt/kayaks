<?php
add_filter('manage_edit-pa_farben_columns',  'hoang_product_attribute_columns');
add_filter('manage_pa_farben_custom_column', 'hoang_product_attribute_column', 10, 3);
add_action('pa_farben_add_form_fields','hoang_add_attribute_thumbnail_field');
add_action('pa_farben_edit_form_fields','hoang_edit_attributre_thumbnail_field', 10, 2);
add_action('created_term', 'hoang_attribute_thumbnail_field_save', 10, 3);
add_action('edit_term','hoang_attribute_thumbnail_field_save', 10, 3);

 //Registers a column for this attribute taxonomy for this image
    function hoang_product_attribute_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumb'] ='Hoang';
        unset($columns['cb']);

        $columns = array_merge($new_columns, $columns);
        return $columns;
    }

    //Renders the custom column as defined in woocommerce_product_attribute_columns
    function hoang_product_attribute_column($columns, $column, $id) {
    	$taxonomy = $_REQUEST['taxonomy'];
    	$thumb_id = get_woocommerce_term_meta($id,'pa_farben_hoang_photo',true);
    	$thumb_src = wp_get_attachment_image_src($thumb_id)[0];
    	$thumb_src = $thumb_src=='' ? plugins_url("woocommerce/assets/images/placeholder.png") : $thumb_src;
        if ($column == 'thumb') :
            $columns .= "<img src='".$thumb_src."' width=32px height=32px>";
        endif;
        return $columns;
    }
    function hoang_add_attribute_thumbnail_field() {
    	wp_enqueue_media();
    ?>
    	<div class="form-field ">	
    		<label>Thumbnail Image</label>
    		<input type="hidden" id="hoang_color_thumbnail_id" name="hoang_color_thumbnail_id" value="" />
            <div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo plugins_url('woocommerce/assets/images/placeholder.png');?>" width="32px" height="32px" /></div>
            <button type="button" class="upload_image_button button">Hochladen/ Bild hinzufügen</button>
            <button type="submit" class="remove_image_button button">Bild entfernen</button>
    	</div>
    	<script type="text/javascript">

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: 'Choose an image',
						button: {
							text: 'Use image',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#hoang_color_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo plugins_url("woocommerce/assets/images/placeholder.png");?>');
					jQuery('#hoang_color_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

		</script>
		<div class="clearfix"></div>
	<?php    
	}
    function hoang_edit_attributre_thumbnail_field($term, $taxonomy) {
    	wp_enqueue_media ();
    	$thumb_id = get_woocommerce_term_meta($term->term_id,'pa_farben_hoang_photo',true);
    	$thumb_src = wp_get_attachment_image_src($thumb_id)[0];
    	if ($thumb_src=='') $thumb_src=plugins_url("woocommerce/assets/images/placeholder.png");
    ?>
    	<tr class="form-field ">
            <th scope="row" valign="top"><label>Thumbnail Image</label></th>
            <td>
            	<input type="hidden" id="hoang_color_thumbnail_id" name="hoang_color_thumbnail_id" value="<?php echo $thumb_id;?>" />
            	<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $thumb_src;?>" width="32px" height="32px" /></div>
                <button type="button" class="upload_image_button button">Hochladen/ Bild hinzufügen</button>
                <button type="submit" class="remove_image_button button">Bild entfernen</button>
            </td>
        </tr>


		<script type="text/javascript">

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: 'Choose an image',
						button: {
							text: 'Use image',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#hoang_color_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo plugins_url("woocommerce/assets/images/placeholder.png");?>');
					jQuery('#hoang_color_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
    <?php
 	}
    function hoang_attribute_thumbnail_field_save($term_id, $tt_id, $taxonomy){
    	$wert = $_POST['hoang_color_thumbnail_id'];
    	update_woocommerce_term_meta($term_id,$taxonomy.'_hoang_photo',$wert);
    }
//add_action ('hoang_woo_before_single_product_summary', 'hoang_color_variable', 30);
function hoang_color_variable() {
	global $woocommerce, $product, $post;
	if (!$product->is_type('variable')) return;
	$available_variations = $product->get_available_variations();
	$attributes   		  = $product->get_variation_attributes();
	$selected_attributes  = $product->get_variation_default_attributes();
	$name = "pa_farben";
	$options = $attributes[$name];
	$selected_color = get_term_by("slug",$selected_attributes[$name],$name)->name;
	$script_link = get_stylesheet_directory_uri().'/js/add-to-cart-variation-hoang.js';
	wp_enqueue_script( 'hoang-add-to-cart-variation',$script_link ); 
	$output = "";

	$output .= "<div id='hoang_color_variant'>";
	$output .= "	<div id='selected_color_text'>";
	if ($selected_attributes[$name]!=null) {
		$output .= "Ausgewählte Farbe: <span id='selected_color'>".$selected_color."</span>";
	}
	else { 
		$output .= "Wählen Sie bitte eine Farbe aus";
	} 
	$output .= "	</div>";
	if ( is_array( $options ) ) {
	// Get terms if this is a taxonomy - ordered
		if ( taxonomy_exists( $name ) ) {
			$orderby = $woocommerce->attribute_orderby( $name );
			switch ( $orderby ) {
				case 'name' :
							$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
							break;
				case 'id' :
							$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false );
							break;
				case 'menu_order' :
							$args = array( 'menu_order' => 'ASC' );
							break;
			}
			$terms = get_terms( $name, $args );

			foreach ( $terms as $term ) {
				if ( ! in_array( $term->slug, $options ) ) continue;
					$checked = $selected_attributes['pa_farben']==$term->slug ? 'checked':'';
					$thumb_id = get_woocommerce_term_meta($term->term_id,'pa_farben_hoang_photo',true);
					$thumb_src = wp_get_attachment_image_src($thumb_id)[0];
					$output .= "<div class='hoang_variant' wert='".$term->slug."' color_name='".$term->name."'>";
					$output .= "	<input ".$checked." class='hoang_variant_radio' type='radio' name='hoang_color'>";
					$output .= "	<img alt='".$term->name."' src='".$thumb_src."'>";
					$output .= "</div>";
		
			}
		} 
	}
	$output .= "</div>";
	$output .= "<div class='images'><img src='' style='display:none'/></div>";
	return $output;
}