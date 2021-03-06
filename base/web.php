<?php
//处理url index.php?s=/sdf/sdf/sdf/sdf/
define('WEB_PATH',dirname(__FILE__).'/');
include_once(WEB_PATH."route.php");
if(isset($_GET)){
	$_GET = dtrim($_GET);
}
if(isset($_POST)){
	$_POST = dtrim($_POST);
}
if(isset($_REQUEST)){ 
	$_REQUEST = dtrim($_REQUEST);
}
if(isset($_COOKIE)){ 
	$_COOKIE = dtrim($_COOKIE);
}

$hp_path = isset($_GET['s'])?$_GET['s']:'/'; 
//把非重写模式的转为重写模式的
if(env('url_rewrite_off')){
	$hp_m = isset($_GET['m'])?"/".trim($_GET['m']):'/'.env('default_module');
	$hp_c = isset($_GET['c'])?"/".trim($_GET['c']):'/index';
	$hp_a = isset($_GET['a'])?"/".trim($_GET['a']):'/index';
	if(isset($_GET['m'])){
		unset($_GET['m']);
	}
	if(isset($_GET['c'])){
		unset($_GET['c']);
	}
	if(isset($_GET['a'])){
		unset($_GET['a']);
	}
	$hp_temp = '';
	foreach($_GET as $k=>$v){
		$hp_temp .= "/{$k}/{$v}";
	}
	$hp_path = "{$hp_m}{$hp_c}{$hp_a}".$hp_temp;
	if(!$hp_path){
		$hp_path = '/';
	}
}
if($hp_path){
	if(array_key_exists($hp_path, env('route'))){
		$route = env('route');
		$hp_path = $route[$hp_path];
	}
	$hp_env_vars['hp_path'] = $hp_path;
	$hp_path = explode('/',$hp_path);
	$new = array();
	foreach($hp_path as $v){
	if($v !== ''){
			$new[] = $v;
		}
	}
	//模块名
	if(isset($new[0])){
		$m = trim(array_shift($new));
	}else{
		$m = env('default_module');
	}
	if(!in_array($m, env('valid_modules'),true)){
		//跳到404页面
		show_404("找不到模块，不是合法的模块:$m");
	}

	$hp_env_vars['current_module'] = $m;
	//action名
	if(isset($new[0])){
		$c = trim(array_shift($new));
		$c = $c.'_action';
	}else{
		$c = 'index_action';
	}
	$hp_env_vars['current_controller'] = $c;
	//action的方法名
	if(isset($new[0])){
		$a = trim(array_shift($new));
	}else{
		$a = 'index';
	}
	$hp_env_vars['current_action'] = $a;
	if($new){
		$get = array();
		$count = count($new);
		$count = ceil($count/2);
		for($i=0;$i<$count;$i++){
			$get[array_shift($new)] = array_shift($new);
		}
		$newget = array();
		foreach($get as $key=>$var){
			$newget[addslashes($key)] = addslashes($var);
		}
		$_GET = array_merge($_GET, $newget);
	}

	foreach($_GET as &$v1){
		if(!is_array($v1)){
			$v1 = dtrim($v1);
		}
	}
	foreach($_POST as &$vv1){
		if(!is_array($vv1)){
			$vv1 = dtrim($vv1);
		}
	}
	foreach($_REQUEST as &$vv2){
		if(!is_array($vv2)){
			$vv2 = dtrim($vv2);
		}
	}
	
	 
	require_once(WEB_PATH."init.php");
	$current_module = env('current_module');
	if($current_module){
		//载入当前模块的init.php文件
		include_once(WEB_PATH."../module/{$current_module}/init.php");
	}
	 
	session_start();
	//运行action
	if(class_exists($c)){
		$action = new $c ;

		$action->_a();
		$action->_remap($a);
		$action->_z();
		 
	}else{
		show_404("找不到控制器:$c");
	}
}	 
