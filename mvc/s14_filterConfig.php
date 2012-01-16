<?php
Log::getInstance()->write(__FILE__." loaded", "debug");
class FilterConfiguration {
	public $filters = array(
		"authorisation" => array(
			"file" => "filters/authorisation.filter.php",
			"class" => "AuthorisationFilter"
		),
		"clickhistory" => array(
			"file" => "filters/clickhistory.filter.php",
			"class" => "ClickHistory"
		)
	);
}
?>