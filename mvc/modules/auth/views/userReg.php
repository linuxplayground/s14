<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class UserRegView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function registrationForm ($oMsg) {
		$oMsg->response = $this->execute($oMsg->data['xml'], 'modules/auth/views/templates/registerUserForm.xsl') ;
	}
}
?>