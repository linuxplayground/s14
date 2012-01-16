<?php
		
	Log::getInstance()->write (__FILE__." loaded", "debug" );

class Configuration {
	
	public $actions = array (
		"default" => array (
			"file" => "modules/default.php",
			"class" => "HomePageModel",
			"method" => "execute",
			"results" => array (
				"error" => array ( "view" => "general_error" ),
				"display_news" => array ("action" => "listNews",
									"module" => "news" )
			)
		)
	);
	
	public $views = array (
		"general_error" => array (
			"file" => "modules/common/error/general_error.php",
			"class" => "GeneralError",
			"method" => "execute"
		),
		
		"exessive_view_loop" => array (
			"file" => "modules/common/error/general_error.php",
			"class" => "GeneralError",
			"method" => "execute"
		)
	);
}
?>