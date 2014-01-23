<?php
define('CORE_FUNC_PATH',dirname(__FILE__).'/');
include_once(CORE_FUNC_PATH.'env_vars.php');

//实例化并且单例
function single($class){
	return factory::make_one_obj($class);
}

//看sql是不是以某开头
function sql_start( $sql ){
	$str = trim($sql,'()');
	$needles = func_get_args();
	array_shift( $needles );
	foreach( $needles as $var ){
		if(strpos(trim(strtolower($sql)), strtolower($var)) === 0 ){
			return true;
		}
	}
	return false;
}

function sys_exit($msg=null){
	exit($msg);
}
