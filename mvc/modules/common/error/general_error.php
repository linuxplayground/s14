<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );
require_once ("modules/common/template_generic.class.php");

class GeneralError extends GenericTemplate {
	public function __construct ( ) {
		//
	}
	
	public function execute ($oMsg) {
	
		parent::__construct( 'modules/common/templates/general_error.tpl' );
		$this->assign ("ERRORTEXT", $oMsg->data['error']);
		$this->assign("BACKLINK", $oMsg->data['backlink']);

		$oMsg->response = $this->getGenericTemplate( );
	}
}
?>