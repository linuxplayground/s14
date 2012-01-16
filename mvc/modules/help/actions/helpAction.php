<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ("modules/common/model.class.php");

class HelpAction extends Model {
	public function __construct ( ) {
		//
	}
	//HERE YOU SIMPLY SPECIFY A RESULT FOR THE CONFIGURATION
	public function showHelp( $oMsg ) {
		$oMsg->result = 'showHelp';
	}
	public function pleasePay( $oMsg ) {
		$oMsg->result = 'pleasePay';
	}
}

?>