<?php
require_once ( apply_filters('template_directory',get_template_directory()."/header.php"));
	if (!is_front_page()) {?>
		<div id='header_slider'>
		<?php
			header_slider();	// locate in functions-hoang.php
		?>
		</div>	<!-- End header_slider -->
	<?php }