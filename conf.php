<?php
define('CONF_PATH',dirname(__FILE__).'/');

/**一般配置++++++++++++++++++++*******************************************/
ini_set("session.use_only_cookies",1);
date_default_timezone_set("Asia/Shanghai");


//全局变量放在这里
$hp_env_vars = array();
require_once(CONF_PATH.'base/core/funcs/hp_load_funcs.php');
/**配置URL地址***********************************************************/
define('URL', $_SERVER['HTTP_HOST'].'/');
//公共html地址 
define('BASE_HTML_URL','http://'.URL.'base/html/');

/**数据库配置+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++**/
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


/**启用的模块列表++++++++++_++++++++++++++++++++++++++++++++++++++++++++**/
$hp_valid_modules = array('test');
$hp_env_vars['valid_modules'] = $hp_valid_modules;

/**默认模块++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++**/
 
$hp_default_module = 'test';
$hp_env_vars['default_module'] = $hp_default_module;

/**url模式+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++**/


/**是否是开发模式+++ true or false +++++++++++++++++++++++++++++++++++**/
$hp_dev_mode = true;
$hp_env_vars['dev_mode'] = $hp_dev_mode;

/**是否记录错误退出信息到日志文件+++ true or false +++++++++++++++++++++**/
$hp_log_user_runtime = false;
$hp_env_vars['log_user_runtime'] = $hp_log_user_runtime; 
/**记录错误退出信息日志的路径 +++  ++++++++++++++++++++++++++++++++++++**/
$hp_log_dir = CONF_PATH.'files/logs/';
$hp_env_vars['log_dir'] = $hp_log_dir;