<?php

	class ShowBizFront extends UniteBaseFrontClassBiz{
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct($mainFilepath){
			
			parent::__construct($mainFilepath,$this);
			GlobalsShowBiz::initGlobals();			
		}		
		
		/**
		 * 
		 * a must function. you can not use it, but the function must stay there!.
		 */		
		public static function onAddScripts(){
			
			self::addStyle("settings","showbiz-settings","showbiz-plugin/css");
						
			$url_jquery = "http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js?app=showbiz";
			self::addScriptAbsoluteUrl($url_jquery, "jquery");
			
			if(GlobalsShowBiz::INCLUDE_FANCYBOX == true){
				self::addStyle("jquery.fancybox","fancybox","showbiz-plugin/fancybox");
				self::addScript("jquery.fancybox.pack","showbiz-plugin/fancybox","fancybox");
			}
			
			self::addScript("jquery.themepunch.showbizpro.min","showbiz-plugin/js");
		}
		
	}
	

?>