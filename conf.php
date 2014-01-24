<?php
define('CONF_PATH',dirname(__FILE__).'/');

/**一般配置++++++++++++++++++++**/
ini_set("session.use_only_cookies",1);
date_default_timezone_set("Asia/Shanghai");


//全局变量放在这里
$hp_env_vars = array();
require_once(CONF_PATH.'base/core/funcs/hp_load_funcs.php');


/**数据库配置++++++++++++++++++**/


$hp_db_configs = array();
$hp_db_configs['default'] = array(
		'dsn' => 'mysql:dbname=hp;host=localhost',
		'user' => 'root',
		'password' => '',
	);

$hp_db_configs['test'] = array(
		'dsn' => 'mysql:dbname=test;host=localhost',
		'user' => 'root',
		'password' => '',
	); 
 
$hp_env_vars['db_config'] = $hp_db_configs;

 
/**默认模块++++++++++++++++++++**/
 
$hp_default_module = 'test';
$hp_env_vars['default_module'] = $hp_default_module;
 


/**是否是开发模式+++ true or false +++++++++++**/
$hp_dev_mode = false;
$hp_env_vars['dev_mode'] = $hp_dev_mode;

