<?php
	Log::getInstance()->write (__FILE__." loaded", "debug" );
	class Message {
		public $action;
		public $module;
		public $filter;
		public $request;
		public $view = 'none';
		public $data;
		public $result;
		public $response = 'none';
		public $name;
		public $fragment;
		public $base;
		public $conn;
		
		public $message;
		
		public function __Construct ( $name, $fragment ) {
			$this->name = $name;
			$this->fragment = $fragment;
		}
	}
?>