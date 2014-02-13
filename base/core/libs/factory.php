<?php
class factory{
	static protected $instance = array();

	static public function make_one_obj($class){
		 
		if(!class_exists($class)){
			sys_exit("class $class does not exist");
		}
		
		if(!isset(self::$instance[$class])){
			self::$instance[$class] = new $class;
		}
		$obj =  self::$instance[$class];
		if(method_exists($obj,'_renew')){
			$obj->_renew();
		}
		return $obj;
	}



}