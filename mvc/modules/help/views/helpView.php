<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );
require_once ("modules/common/template_generic.class.php");

class HelpView extends GenericTemplate {
	public function __construct ( ) {
		//
	}
	
	public function showHelp ($oMsg) {
		parent::__construct( 'modules/help/views/templates/help.tpl' );
		$this->assign("BACKLINK", $oMsg->data['backlink']);
		$oMsg->response = $this->getGenericTemplate( );
	}
	public function pleasePay ($oMsg) {
		parent::__construct( 'modules/help/views/templates/pleasePay.tpl' );
		$this->assign("BACKLINK", $oMsg->data['backlink']);
		$oMsg->response = $this->getGenericTemplate( );
	}
}
?>