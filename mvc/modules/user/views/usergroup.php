<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class UserGroupView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function userGroupSelectPage( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['getUserGroupData']['xml'], 'modules/user/views/templates/userGroups.xsl') ;
	}
}