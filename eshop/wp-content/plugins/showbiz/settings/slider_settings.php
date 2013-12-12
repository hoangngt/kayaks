<?php
	
	//set "slider_main" settings
	$sliderMainSettings = new UniteSettingsAdvancedBiz();
	$sliderMainSettings->addTextBox("title", "","Slider Title",array("description"=>"The title of the slider. Example: Slider1","required"=>"true"));	
	$sliderMainSettings->addTextBox("alias", "","Slider Alias",array("description"=>"The alias that will be used for embedding the slider. Example: slider1","required"=>"true"));
	$sliderMainSettings->addTextBox("shortcode", "","Slider Short Code",array("readonly"=>true,"class"=>"code"));
	$sliderMainSettings->addHr();
	
	//source type
	$arrSourceTypes = array("posts"=>"Posts",
							  "specific_posts"=>"Specific Posts",
							  "gallery"=>"Gallery");
	
	if(UniteFunctionsWooCommerceBiz::isWooCommerceExists())
		$arrSourceTypes["woocommerce"] = "WooCommerce";
	
	$sliderMainSettings->addRadio("source_type",$arrSourceTypes,"Source Type", "posts"); 
						
	//post categories list
	$sliderMainSettings->startBulkControl("source_type", UniteSettingsBiz::CONTROL_TYPE_SHOW, "posts");

		//post types
		$arrPostTypes = UniteFunctionsWPBiz::getPostTypesAssoc();
		$arrParams = array("args"=>"multiple size='5'");
		$sliderMainSettings->addSelect("post_types", $arrPostTypes, "Post Types","post",$arrParams);
	
		//post categories
		$arrParams = array("args"=>"multiple size='7'");
		$sliderMainSettings->addSelect("post_category", array(), "Post Categories","",$arrParams);
				
		//sort by
		$arrSortBy = UniteFunctionsWPBiz::getArrSortBy();
		$sliderMainSettings->addSelect("post_sortby", $arrSortBy, "Sort Posts By",ShowBizSlider::DEFAULT_POST_SORTBY);
		
		//sort direction
		$arrSortDir = UniteFunctionsWPBiz::getArrSortDirection();
		$sliderMainSettings->addRadio("posts_sort_direction", $arrSortDir, "Sort Direction", ShowBizSlider::DEFAULT_POST_SORTDIR);
		
		//max posts for slider
		$arrParams = array("class"=>"small","unit"=>"posts");
		$sliderMainSettings->addTextBox("max_slider_posts", "30", "Max Posts Per Slider", $arrParams);
		
		$arrParams = array("class"=>"small","unit"=>"words");		
		$sliderMainSettings->addTextBox("excerpt_limit", "55", "Limit The Excerpt To", $arrParams);

		
	$sliderMainSettings->endBulkControl();
	
	// -------------- Specific IDs ------------------
	
	$arrParams = array("description"=>"Type here the post IDs you want to use separated by coma. ex: 23,24,25");
	$sliderMainSettings->addTextBox("posts_list","","Specific Posts List",$arrParams);
	$sliderMainSettings->addControl("source_type", "posts_list", UniteSettingsBiz::CONTROL_TYPE_SHOW, "specific_posts");

	
	// -------------- Woo Commerce ------------------
	if(UniteFunctionsWooCommerceBiz::isWooCommerceExists()):
	
	
		$wcPostTypes = UniteFunctionsWooCommerceBiz::getCustomPostTypes();
		$sliderMainSettings->startBulkControl("source_type", UniteSettingsBiz::CONTROL_TYPE_SHOW, "woocommerce");
		
			$arrParams = array("args"=>"multiple size='2'");
			$sliderMainSettings->addSelect("wc_post_types", $wcPostTypes, "Post Types","post",$arrParams);
			
			//post categories
			$arrParams = array("args"=>"multiple size='7'");
			$sliderMainSettings->addSelect("wc_post_category", array(), "Post Categories","",$arrParams);
			
			//sort by
			$arrSortBy = UniteFunctionsWooCommerceBiz::getArrSortBy();
			$sliderMainSettings->addSelect("wc_post_sortby", $arrSortBy, "Sort Posts By",ShowBizSlider::DEFAULT_POST_SORTBY);
			
			//sort direction
			$arrSortDir = UniteFunctionsWPBiz::getArrSortDirection();
			$sliderMainSettings->addRadio("wc_posts_sort_direction", $arrSortDir, "Sort Direction", ShowBizSlider::DEFAULT_POST_SORTDIR);
			
			//max posts for slider
			$arrParams = array("class"=>"small","unit"=>"posts");
			$sliderMainSettings->addTextBox("wc_max_slider_posts", "30", "Max Posts Per Slider", $arrParams);
			
			$sliderMainSettings->addStaticText("------ WooCommerce Filters ---------","text_filters");
			
			$arrParams = array("class"=>"small",
								UniteSettingsBiz::PARAM_ADDTEXT_BEFORE_ELEMENT=>"From:" );
				
			
			$sliderMainSettings->addTextBox("wc_regular_price_from", "", "Regular Price", $arrParams);
			$arrParams = array("class"=>"small",
							   UniteSettingsBiz::PARAM_OUTPUTWITH=>"wc_regular_price_from",
							   UniteSettingsBiz::PARAM_ADDTEXT_BEFORE_ELEMENT=>"&nbsp;&nbsp;&nbsp;&nbsp; To:"
							   );
							   
			
			$sliderMainSettings->addTextBox("wc_regular_price_to", "", "Regular Price To", $arrParams);
	
			$arrParams = array("class"=>"small",
								UniteSettingsBiz::PARAM_ADDTEXT_BEFORE_ELEMENT=>"From:"
								);
				
			$sliderMainSettings->addTextBox("wc_sale_price_from", "", "Sale Price", $arrParams);
			
			$arrParams = array("class"=>"small",
							   UniteSettingsBiz::PARAM_OUTPUTWITH=>"wc_sale_price_from",
							   UniteSettingsBiz::PARAM_ADDTEXT_BEFORE_ELEMENT=>"&nbsp;&nbsp;&nbsp;&nbsp; To:"
							   );
			
			$sliderMainSettings->addTextBox("wc_sale_price_to", "", "Sale Price To", $arrParams);
			$sliderMainSettings->addCheckbox("wc_instock_only",false,"In Stock Only");
			$sliderMainSettings->addCheckbox("wc_featured_only",false,"Featured Products Only");
			
			
		$sliderMainSettings->endBulkControl();
		
	endif;		//if woocommerce exists
	
	
	// -------------- End Filters ------------------
	
	$sliderMainSettings->addHr();
	
	//set select item template
	$templates = new ShowBizTemplate();
	$arrTemplates = $templates->getArrShortAssoc(GlobalsShowBiz::TEMPLATE_TYPE_ITEM);
	
	$addHtml = "<a href='javascript:void(0)' id='button_edit_item_template' class='button-secondary mleft_10'>Edit</a>";
	$params = array("description"=>"The template that set the look of the item",UniteSettingsBiz::PARAM_ADDTEXT => $addHtml);
	$sliderMainSettings->addSelect("template_id", $arrTemplates, "Item Template", "", $params);
	
	self::storeSettings("slider_main",$sliderMainSettings);
	
	//set "slider_params" settings. 
	$sliderParamsSettings = new UniteSettingsAdvancedBiz();	
	$sliderParamsSettings->loadXMLFile(self::$path_settings."/slider_settings.xml");
	
	//update navigation template
	$templates = new ShowBizTemplate();
	$arrTemplates = $templates->getArrShortAssoc(GlobalsShowBiz::TEMPLATE_TYPE_BUTTON);
	$addHtml = "<a href='javascript:void(0)' id='button_edit_item_template_nav' class='button-secondary mleft_10' style='margin-top:17px;'>Edit</a>";
	
	$sliderParamsSettings->updateSettingField("nav_template_id", "items", $arrTemplates);
	$sliderParamsSettings->updateSettingField("nav_template_id", UniteSettingsBiz::PARAM_ADDTEXT, $addHtml);
	
	//store params
	self::storeSettings("slider_params",$sliderParamsSettings); 
	
?>

