<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ("modules/common/model.class.php");

class HomePageModel extends Model {
	public function __construct ( ) {
		//
	}
	//HERE YOU SIMPLY SPECIFY A RESULT FOR THE CONFIGURATION
	public function execute ( $oMsg ) {
		if ( ! isset( $oMsg->message) )
			$oMsg->message = 'Look here for help and error messages.';
		$oMsg->result = 'display_news';
	}
}

?>