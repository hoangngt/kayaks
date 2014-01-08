<?php
/**
 * Automatic class Loader
 * @author ap
 */
class WGM_Loader {

	public static function register(){
		spl_autoload_register( 'WGM_Loader::load' );
	}

	public static function unregister(){
		spl_autoload_unregister( 'WGM_Loader::load' );
	}

	public static function load( $classname ){
		$file =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR . ucfirst( $classname ) . '.php';
			
		if( file_exists( $file ) ) require_once $file;
	}
}
?>