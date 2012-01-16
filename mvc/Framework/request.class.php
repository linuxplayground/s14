<?php

require_once ( "global.php" );
Log::getInstance()->write ( __FILE__." loaded", "debug" );
require_once ( "message.class.php" );
require_once ( "controller.class.php" );

require_once ( "MDB2.php" ); //PEAR MDB2 requires also PEAR mdb2_driver_mysql

class Request {
	
	public $db; //Container for database connection
	
	public function __construct( ) {
		
		
		$dsn = "mysql://s14:@localhost/s14";
		$this->db = & MDB2::connect( $dsn );
		if (PEAR::isError( $this->db) ) {
			Log::getInstance()->write("Could not connect to database.".mysql_error()." - ".$this->db>getMessage(),"error");
			die("Could not connect to database.".$this->db>getMessage());
		} else {
			Log::getInstance()->write("Connected to database", "debug");
		}
	}
	function execute ( $name, $fragment = "default" ) {

		$oMsg = new Message ( $name, $fragment );
		$oMsg->conn =& $this->db;
		
		//INITIATE CONFIGURATION FOR APPLICATION (FRAMEWORK)
		$confFile = $name."_config.php";
		require_once ($confFile);
		$oConf = new Configuration ( $oMsg );
		
		//INITIATE CONFIGURATION FOR MODULE IF A MODULE IS DEFINED
		//IS A MODULE DEFINED?
		if( array_key_exists( "module", $_POST ) ) {
			$module = $_POST['module'];
		} elseif ( array_key_exists( "module", $_REQUEST) ) {
			$module = $_REQUEST['module'];
		} else {
			$module = false;
		}
		$modConfFile = "modules/".$module."/".$module."_conf.php";
		if( !file_exists($modConfFile) && $module ) {
			$module = false;
			Log::getInstance()->write("REQUESTER ERROR:> Module configuration file does not exist.", "error");
		}
		
		if ($module) {  //only do this if we actually have a module.  Default actions and views are included in application config.
			include_once ($modConfFile);
			
			//DEFINE THE MODULE ACTIONS = moduleName+"_actions"
			$moduleActions = $module."Actions";
			//DEFINE THE MODULE VIEWS = moduleName+"_views"
			$moduleViews = $module."Views";
			
			//MERGE THE ARRAYS IN FRAMEWORK oCONF WITH THOSE IN THE MODULE CONF
			if ( !isset($$moduleActions) ) {
				Log::getInstance()->write("REQUESTER ERROR:> Error with module actions.", "error");
			} else {
				$oConf->actions = array_merge( $oConf->actions, $$moduleActions );
			}
			if ( !isset($$moduleViews)) {
				Log::getInstance()->write("REQUESTER ERROR:> Error with module views.", "error");
			} else {
				$oConf->views = array_merge( $oConf->views, $$moduleViews );
			}
		}
		
		//ADD SOME INFORMATION TO THE MESSAGE FOR LATER USE
		$oMsg->data['application']['name'] = $name;
		$oMsg->data['application']['module'] = $module;
		$oMsg->base = dirname(__FILE__)."/../"; //BASE PATH OF MVC
		
		//DEFINE THE ACTION
		if( array_key_exists( "action", $_POST) ) {
			$oMsg->request = $_POST;
		} else {
			$oMsg->request = $_REQUEST;
			if (! array_key_exists("action", $oMsg->request)) {
				$oMsg->request['action'] = "default";
			}
		}
		$oMsg->action = $oMsg->request['action'];
		
		
		//PROCEED WITH THE CONTROLLER.
		$oController = new Controller ( $oConf );
		//PROCEED WITH FILTER CHAIN
		require_once("filter.class.php");
		$fc = new FilteredController($oController, $oMsg->name);
		$fc->process($oMsg);
		
		Log::getInstance()->write ("************** END ************", "debug" );
				
		//echo "<pre>"; print_r($oMsg); echo "</pre>";
		
			
		return $oMsg->response;
	}
	public function __destruct ( ) {

		$this->db->disconnect();
		Log::getInstance()->write("Disconnected from database", "debug");
		if ( array_key_exists('systemUser', $_SESSION ) ) {
			$_SESSION = array();
			session_destroy();
		}
	}
}
?>