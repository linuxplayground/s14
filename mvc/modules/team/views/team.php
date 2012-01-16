<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class TeamView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function listTeam ($oMsg) {
	
		$oMsg->response = $this->execute($oMsg->data['listTeam']['xml'], 'modules/team/views/templates/listTeams.xsl');
	}
	
	public function newTeam ($oMsg) {
		$oMsg->response = $this->execute($oMsg->data['newTeam']['xml'], 'modules/team/views/templates/editTeam.xsl') ;
	}

	public function editTeam ($oMsg) {
		$oMsg->response = $this->execute($oMsg->data['editTeam']['xml'], 'modules/team/views/templates/editTeam.xsl') ;
	}
	
	public function confirmDelete ( $oMsg ) {
		$oMsg->response = $this->execute ( $oMsg->data['confirmDelete']['xml'], 'modules/team/views/templates/confirmDeleteTeam.xsl' );
	}
}
?>