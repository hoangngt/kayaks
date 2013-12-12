	
	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>
			Edit Slide <?php echo $slideOrder?>, title: <?php echo $slideTitle?> 
		</h2>
		
		<div id="slide_params_holder">
			<form name="form_slide_params" id="form_slide_params">		
			<?php
				$settingsSlideOutput->draw("form_slide_params",false);
			?>
				<input type="hidden" id="image_url" name="image_url" value="<?php echo $imageUrl?>" />
			</form>
		</div>
		
		<div class="vert_sap_medium"></div>
		
		<div class="slide_update_button_wrapper">
			<a href="javascript:void(0)" id="button_save_slide" class="orangebutton">Update Slide</a>
			<div id="loader_update" class="loader_round" style="display:none;">updating...</div>
			<div id="update_slide_success" class="success_message" class="display:none;"></div>
		</div>
		<a id="button_close_slide" href="<?php echo $closeUrl?>" class="button-primary">Close</a>
		
	</div>
	
	<div class="vert_sap"></div>
		
	<script type="text/javascript">
		jQuery(document).ready(function(){
			ShowBizAdmin.initEditSlideView(<?php echo $slideID?>);
		});
	</script>
	
	
