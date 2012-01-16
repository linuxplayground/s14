<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class GameView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listAllGame ( Message $oMsg ) {

		$oMsg->response = $this->execute($oMsg->data['game']['xml'], "modules/game/views/templates/listAllGame.xsl");
	}
	
	public function editGame ( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['game']['xml'], "modules/game/views/templates/editGame.xsl");
	}
	
	public function newGame ( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['game']['xml'], "modules/game/views/templates/newGame.xsl");
	}
	
	public function confirmDeleteGameForm( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['game']['xml'], "modules/game/views/templates/confirmDeleteGame.xsl");
	}

}
?>