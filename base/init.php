<?php
define('INIT_PATH',dirname(__FILE__).'/');
define('TOOL_PATH',INIT_PATH.'tool/');
//自动载入
function loader( $class ){
	$current_module = env('current_module');
	 
	if(file_exists(INIT_PATH.'core/libs/'.$class.'.php')){
		include_once( INIT_PATH.'core/libs/'.$class.'.php' );
	}elseif(file_exists(INIT_PATH.'libs/'.$class.'.php')){
		include_once( INIT_PATH.'libs/'.$class.'.php');
	}elseif(file_exists(INIT_PATH.'class/'.$class.'.php')){
		include_once( INIT_PATH.'class/'.$class.'.php');
	}elseif(file_exists(INIT_PATH.'tables/'.$class.'.php')){
		include_once( INIT_PATH.'tables/'.$class.'.php');
	}
	if($current_module){
		if(file_exists(INIT_PATH."../module/{$current_module}/action/".$class.'.php')){
			include_once( INIT_PATH."../module/{$current_module}/action/".$class.'.php' );
		}
		if(file_exists(INIT_PATH."../module/{$current_module}/class/".$class.'.php')){
			include_once( INIT_PATH."../module/{$current_module}/class/".$class.'.php' );
		}
	}
}
spl_autoload_register('loader');
include_once(INIT_PATH.'funcs/load_funcs.php');
//载入对应模块的函数
$current_module = env('current_module');
if($current_module){
	include_once(INIT_PATH."../module/{$current_module}/funcs/load_funcs.php");
}