;(function ( $, window, document, undefined ) {

    $(function() {
    	$("div.entry-content-wrapper > form").hide();
    	var $open = false;
    	var $show_form_text = $('#show_form_text').val();
    	var $close_form_text = $('#close_form_text').val();
    	$("a#show_registration_form").click(function(e){
    		e.preventDefault();
    		if ($open == false) {
    			$open = true;
	    		$(this).html($close_form_text);
	    		$("div.entry-content-wrapper > form").slideDown(200);
    		}
    		else {
    			$open = false;
	    		$(this).html($show_form_text);
	    		$("div.entry-content-wrapper > form").slideUp(200);
    		}
    	});
    });

})( jQuery, window, document );