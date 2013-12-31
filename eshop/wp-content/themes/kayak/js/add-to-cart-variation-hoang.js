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
        
        $('.avia-gallery-thumb a').css('width','30%');
        $('#hoang_color_variant').css('top',$('.avia-gallery-big').height()+10);
    	$('#hoang_color_variant').on("change",".hoang_variant_radio", function(e) {      /* whenever Customer click on color-img, do this function */
    		e.preventDefault();
    		$var_form = $('.variations select');
    		$var_form.val($(this).val());
    		$var_form.change();                               /* change value of "Color"-select form */
    		$('.single_variation_wrap').css('display','inline');  /* fix bug for Quantity and Add to cart button */
            $('p.stock').hide();                            /* Hide On Stock text */
            $('a.avia-gallery-big img').attr('src',$product_img.attr('src'));
    	});
    });

})( jQuery, window, document );