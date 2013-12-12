
	<table id="list_templates" class='wp-list-table widefat fixed unite_table_items'>
		<thead>
			<tr>
				<th width='25%'>Title</th>
				<th width='30%'>Content</th>
				<th width=''>Operations</th>
				<th width='10%'>Preview</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($arrTemplates as $template):

				$title = $template->getTitle();
				$id = $template->getID();

				if(!empty($filterID) && $id != $filterID)
					continue;

				$title = htmlspecialchars($title);

			?>
				<tr>
					<td><?php echo $title?></td>
					<td>
						<div class="edit_html_button_wrapper">
							<a data-id="<?php echo $id?>" data-title="<?php echo $title?>" href='javascript:void(0)' class="button-secondary button_edit_content">Edit HTML</a>
							<span class="loader_round loader_edit_contents" style="display:none;"></span>
						</div>
						<a data-id="<?php echo $id?>" href='javascript:void(0)' data-title="<?php echo $title?>" class="button-secondary button_edit_css">Edit CSS</a>
						<span class="loader_round loader_edit_css" style="display:none;"></span>
					</td>
					<td>
						<a data-id="<?php echo $id?>" data-title="<?php echo $title?>" href='javascript:void(0)' class="button-secondary button_rename_template changemargin2 newlineheight">Rename</a>
						<a data-id="<?php echo $id?>" href='javascript:void(0)' class="button-secondary button_delete_template changemargin newlineheight">Delete</a>
						<a data-id="<?php echo $id?>" href='javascript:void(0)' class="button-secondary button_duplicate_template changemargin2 newlineheight">Duplicate</a>
						<a data-id="<?php echo $id?>" href='javascript:void(0)' class="button-secondary button_restore_template changemargin2 newlineheight">Restore</a>
					</td>
					<td>
						<a data-id="<?php echo $id?>" data-title="<?php echo $title?>" href='javascript:void(0)' class="button-secondary button_preview_template">Preview</a>
					</td>
				</tr>
			<?php endforeach;?>

		</tbody>
	</table>

	<div id="dialog_rename" class="dialog_rename_template" title="Rename Title" style="display:none;">

		<div class="mtop_15 mbottom_5">
			Enter new title:
		</div>
		<input type="text" id="template_title" >

	</div>

	<div id="dialog_restore" class="dialog_restore" title="Restore Template" style="display:none;">

		<div class="mtop_15 mbottom_5">
			Choose template to restore
		</div>

		<?php
			$select = UniteFunctionsBiz::getHTMLSelect($arrOriginalTemplates,"","id='original_template'");
			echo $select;
		?>
	</div>

	<div id="dialog_content" class="dialog_edit_content" title="Edit Template Html" style="display:none;">

		 <div id="template_buttons_html" class="template_buttons">

		 	<b class="opt_title"><?php echo $standartOptionsName?></b>
			<div class="divide8"></div>

		 	<?php foreach($arrButtons as $name=>$title):
		 		if($name == "break"){
		 			echo "<br>";
		 			continue;
		 		}
		 		$buttonClass = "button-option";
		 		if(strpos($name, "showbiz_wc_") !== false)
		 			$buttonClass .= " button-woocommerce";
		 	?>

		 	<a class="button-secondary <?php echo $buttonClass?>" data-placeholder="<?php echo $name?>" href="javascript:void(0)"><?php echo $title?></a>
		 	<?php endforeach?>

		 	<?php if($showCustomOptions == true):?>

				<hr>

				<div id="template_custom_options_wrapper" class="mtop_10">
					<b class="opt_title">Custom Options</b>
					<div class="divide8"></div>
					<?php
						foreach($arrWildcards as $name=>$title): ?>
							<a id="template_button_<?php echo $name?>" class="button-secondary button-option button-custom" data-placeholder="<?php echo $name?>" href="javascript:void(0)"><?php echo $title?></a>
						<?php
						endforeach;
					?>

		 			<a id="button_edit_custom_options" class="button-secondary" data-placeholder="" href="javascript:void(0)">Add / Edit</a>

		 		</div>

		 	<?php endif?>

		 	<?php if($showClasses == true): ?>
			<hr>

		 	<div id="template_classes" class="mtop_10">
		 		<b class="opt_title">Markup Shortcuts</b>
				<div class="divide8"></div>
		 		<?php
		 			foreach($arrClasses as $class){
		 				$name = UniteFunctionsBiz::getVal($class, "name");
		 				$description = UniteFunctionsBiz::getVal($class, "description");
		 				$html = UniteFunctionsBiz::getVal($class, "html");

		 				?>
		 				<a class="button-secondary button-class"  title='<?php echo $description?>' data-html='<?php echo $html?>' href="javascript:void(0)"><?php echo $name?></a>
		 				<?php

		 			}
		 		?>
		 	</div>
		 	<?php endif?>

		 </div>

		 <textarea id="textarea_content" class="textarea_content"></textarea>
	</div>

	<div id="dialog_css" class="dialog_edit_css" title="Edit Template CSS" style="display:none;">

		 <div id="template_buttons_css" class="template_buttons">

		 	<a class="button-secondary" data-placeholder="itemid" href="javascript:void(0)">Item ID</a>

		 </div>

		 <textarea id="textarea_css" class="textarea_css"></textarea>
	</div>


	<div id="dialog_preview_sliders" class="dialog_preview_sliders" title="Preview Template" style="display:none;">
		<iframe id="frame_preview_slider" name="frame_preview_slider"></iframe>
	</div>


	<form id="form_preview" name="form_preview" action="" target="frame_preview_slider" method="post">
		<input type="hidden" name="client_action" value="preview_template">
		<input type="hidden" id="preview_templateid" name="templateid" value="">
	</form>

