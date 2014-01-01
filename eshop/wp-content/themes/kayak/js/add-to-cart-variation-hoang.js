/*!
 * Variations Plugin
 */
;(function ( $, window, document, undefined ) {

    $(function() {
        var $product_img    = $('div.images img:eq(0)');
        img_src = $product_img.attr('src');
        if (img_src!='') {
            $('a.avia-gallery-big img').attr('src',img_src);
        }
    	$(".hoang_variant").on("click", function(e) {      /* whenever Customer click on color-img, do this function */
            $(this).find('.hoang_variant_radio').prop("checked", true);
    		$var_form = $('.variations select');
    		$var_form.val($(this).attr("wert"));
    		$var_form.change();                 /* change value of "Color"-select form */
            $("#selected_color_text").html("Ausgewählte Farbe: " + $(this).attr("color_name"));
    		$('.single_variation_wrap').css('display','inline');  /* fix bug for Quantity and Add to cart button */                            /* Hide On Stock text */
            $('a.avia-gallery-big img').attr('src',$product_img.attr('src'));
    	});
    });

})( jQuery, window, document );