<?php

Log::getInstance()->write (__FILE__." loaded", "debug" );

class Controller {

	private $base;
	private $oConf;
	private $action_limit;
	private $view_limit;
	
	public function __Construct ( $conf , $action_limit = 30, $view_limit = 5) {
		$this->oConf = $conf;
		$this->base = dirname(__FILE__);
		$this->action_limit = $action_limit;
		$this->view_limit = $view_limit;
	}
	
	private function insert_error ( $oMsg, $text, $view = "general_error") {
		$oMsg->data['error'] = $text;
		$oMsg->view = $view;
		
		return $oMsg;
	}
	
	private function execute_action ($oMsg, $name, $conf ) {
		
		
		/*
		* Validate keys in oConf for action $name
		*/
		
		if ( !array_key_exists ( "file", $conf ) )  {
			Log::getInstance()->write ("no file key in configuration for action ".$name, "error" );
			return $this->insert_error( $oMsg, "no file key in oConf for action ".$name );
		}
		
		if ( !array_key_exists ("class", $conf ) ) {
			Log::getInstance()->write ("no class key in configuration for action ".$name, "error" );
			return $this->insert_error ( $oMsg, "no class key in oConf for action ".$name );
		}
		
		if ( !array_key_exists ("method", $conf ) ) {
			Log::getInstance()->write ("no method key in configuration for action ".$name, "error" );
			return $this->insert_error ( $oMsg, "no method key in oConf for action ".$name );
		}
		
		if ( !array_key_exists ("results", $conf ) ) {
			Log::getInstance()->write ("no results key in oConf for action ".$name, "error" );
			return $this->insert_error ( $oMsg, "no results key in oConf for action ".$name );
		}
		
		/*
		* Dereference to make life easier for me
		*/
		
		$action_file = $conf["file"];
		$action_class = $conf["class"];
		$action_method = $conf["method"];
		$action_results = $conf["results"];
		
		if ( !file_exists ( $action_file ) ) {
			Log::getInstance()->write ("action file ".$action_file. " not found on disk", "error" );
			return $this->insert_error($oMsg, "action file ".$action_file." not found on disk" );
		} else {
			require_once ( $action_file );
		}
		
		if ( !class_exists ($action_class) ) {
			Log::getInstance()->write ("action ".$name." class ".$action_class." not in ".$action_file, "error" );
			return $this->insert_error( $oMsg, "action ".$name." class ".$action_class." not in ".$action_file );
		} else {
			$instance = new $action_class ( );
		}
		
		if ( !method_exists ($instance, $action_method ) ) {
			Log::getInstance()->write ("method ".$action_method." not found in class ".$action_class, "error" );
			return $this->insert_error( $oMsg, "method ".$action_method." not found in class ".$action_class );
		} else {
			$instance->$action_method( $oMsg );
		}
		
		/*
		* Next check for and map the result to the message attributes
		*/
		
		if (!array_key_exists ( $oMsg->result, $action_results ) ) {
			Log::getInstance()->write ("result ".$oMsg->result." not found for action ".$name, "error" );
			return $this->insert_error($oMsg, "result ".$oMsg->result." not found for action ".$name );
		} else {
			$result = $action_results[ $oMsg->result ];
		}
		
		//Insert the result module into the message if it exists.
		if ( array_key_exists( "module", $result ) ) {
			$oMsg->module = $result[ "module" ];
		}
		
		if ( array_key_exists( "action", $result ) ) {
			$oMsg->action = $result[ "action" ];
		} else if ( array_key_exists ("view", $result ) ) {
			$oMsg->view = $result[ "view" ];
		} else {
			Log::getInstance()->write ("result ".$result." not mapped to view or action for action ".$name, "error" );
			return $this->insert_error( $oMsg, "result ".$result." not mapped to view or action for action ".$name );
		}
		
		return $oMsg;
	}
	
	private function create_view( $oMsg, $name, $conf ) {
		
		/*
		* Check that config is all good
		*/
		
		
		if ( !array_key_exists( "file", $conf ) ) {
			Log::getInstance()->write ("no file key in configuration for view ".$name, "error" );
			return $this->insert_error( $oMsg, "no file key in oConf for view ".$name);
		}
		if ( !array_key_exists( "class", $conf ) ) {
			Log::getInstance()->write ("no class key in configuration for view ".$name, "error" );
			return $this->insert_error( $oMsg, "no class key in oConf for view ".$name );
		}
		if ( !array_key_exists( "method", $conf ) ) {
			Log::getInstance()->write ("no method key in configuration for view ".$name, "error" );
			return $this->insert_error( $oMsg, "no method key in oConf for view ".$name );
		}
		
		/*
		* Dereference
		*/
		
		$view_file = $conf["file"];
		$view_class = $conf["class"];
		$view_method = $conf["method"];
		
		/*
		* Check that file, class and method exist then load, instantiate and call.
		*/
		
		if (!file_exists( $view_file) ) {
			Log::getInstance()->write ( "file ".$view_file." not found on disk", "error" );
			return $this->insert_error( $oMsg, "file ".$view_file." not found on disk");
		} else {
			require_once ( $view_file );
		}
		
		if ( !class_exists( $view_class ) ) {
			Log::getInstance()->write ("class ".$view_class." not found in ". $view_file, "error" );
			return $this->insert_error( $oMsg, "class ".$view_class." not found in ".$view_file);
		} else {
			$instance = new $view_class( );
		}
		
		if (!method_exists( $instance, $view_method ) ) {
			Log::getInstance()->write ("method ".$view_method." does not exist in ".$view_class, "error" );
			return $this->insert_error($oMsg, "method ".$view_method." does not exist in ".$view_class);
		} else { 		
			$instance->$view_method( $oMsg );
		}
		
		return $oMsg;
	}
	
	
	public function process ( $oMsg ) {
		
		$configuration = $this->oConf;
		$base = $this->base;
		$counter = 1;
		
		
		
		while ( $oMsg->view == "none" ) {
			//If there is a module in the message then we need to load that module before
			//we can find the action configuration.
			if( isset( $oMsg->module ) ) {
				$modConfFile = "modules/".$oMsg->module."/".$oMsg->module."_conf.php";
				if ( !file_exists( $modConfFile) ) {
					Log::getInstance()->write("CONTROLLER ERROR:> Module configuration file does not exist", "error");
				} else {
					include_once ( $modConfFile );
					$modActionArray = $oMsg->module."Actions";
					$modViewArray = $oMsg->module."Views";
					
					if ( isset( $$modActionArray )) {
						$configuration->actions = array_merge( $configuration->actions, $$modActionArray );
					} else {
						Log::getInstance()->write("CONTROLLER ERROR:> Module actions array is not setup", "error");
					}
					if ( isset( $$modViewArray) ) {
						$configuration->views = array_merge( $configuration->views, $$modViewArray );
					} else {
						Log::getInstance()->write("CONTROLLER ERROR:> Module views array is not setup", "error");
					}
					
					unset ($oMsg->module); 
					//UNSET the module here so that if its reset by a result elswhere the
					//check isset( $oMsg->module ) will work.
				}
			}
			
			if ( array_key_exists ( $oMsg->action, $configuration->actions ) ) {
				$action_configuraion = $configuration->actions[ $oMsg->action ];
				$this->execute_action( $oMsg, $oMsg->action, $action_configuraion );
			} else {
				Log::getInstance()->write ("could not find configuration for action ".$oMsg->action, "error" );
				$this->insert_error($oMsg, "could not find configuration for action ".$oMsg->action );
			}
			
			$counter++;
			
			if( $counter > $this->action_limit ) {
				Log::getInstance()->write ("infinite action loop error", "error");
				$this->insert_error($oMsg, "looks like the action loop got stuck.  Check the config for loops", "exessive_view_loop");
			}
		}
		
		$counter = 1;

		do {
			if ( array_key_exists( $oMsg->view, $configuration->views ) ) {
				$view_conf = $configuration->views[ $oMsg->view ];
				$this->create_view( $oMsg, $oMsg->view, $view_conf );
			} 
			
			else {
				/*
					HERE IS WHERE IT GETS A LITTLE FUNNY.  THIS ERROR APPEARS MYSTERIOUSELY
					IN THE LOGS FOR NO APPERENT REASON WITHOUT THE APP FAILING.
					COMMENT: Found the problem eventually.  It was by pure co-incedence as I was rooting
					arround in the requester that I noticed the controller->process() method was being called twice.
					Once before the configuration was initialised and once after.  The time before generated an error, in
					the log, but nothing else.  The next call, ran the application as expected.
				*/
				Log::getInstance()->write ("no configuration key for view ".$oMsg->view, "error"); //dont want to see this error if I can help it.
				$this->insert_error($oMsg, "no configuration key for view ".$oMsg->view);
			}
			
			
			$counter ++;
			
			if ( $counter > $this->view_limit ) {
			
				Log::getInstance()->write ("infinite view loop error", "error" );
				$this->insert_error($oMsg, "Looks like the view loop got stuck.  Check configuration for loops", "exessive_view_loop");
			}
			
		} while ( ($oMsg->response == "none") && ($oMsg->view != "exessive_view_loop") );
		
		if ( ($oMsg->view == "exessive_view_loop" ) || ( $oMsg->view == "none" ) ) {
			$oMsg->result = $configuration->error;
		}
		
		return $oMsg;
	}
}
?>