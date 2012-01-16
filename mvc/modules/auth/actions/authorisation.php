<?php
/*
	All action methods for Authorisation in this model.
	
*/
require_once ("modules/common/model.class.php");
Log::getInstance()->write (__FILE__." loaded", "debug");

class AuthorisationAction extends Model {

	public function loginForm ( $oMsg ) {
		if ( isset ($_SESSION['token']) ) {
			$oMsg->message = "Logged in already.";
			$oMsg->result = "user_already_logged_in";
		} else {
			require_once ("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			$libxml->push( 'form' );
				$libxml->element( 'message', $oMsg->message );
				$libxml->element( 'action', 'index.php?requestFilter=logIn' );
				$libxml->element( 'method', 'POST' );
				$libxml->element( 'label', 'Log In Form' );
				$libxml->push( 'input' );
					$libxml->element( 'type', 'text' );
					$libxml->element( 'name', 'loginUserName' );
					$libxml->element( 'label', 'User Name' );
				$libxml->pop();
				$libxml->push( 'input' );
					$libxml->element( 'type', 'password' );
					$libxml->element( 'name', 'loginUserPass' );
					$libxml->element( 'label', 'Password' );
				$libxml->pop();
				$libxml->push( 'submit' );
					$libxml->element( 'name', 'loginSubmit' );
					$libxml->element( 'value', 'Log In' );
				$libxml->pop();
			$libxml->pop();
			
			$oMsg->data['login']['xml'] = $libxml->getXml();
			
			$oMsg->result = "display_login_form";
		}
	}
}
?>