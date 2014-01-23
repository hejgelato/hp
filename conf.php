<?php
define('CONF_PATH',dirname(__FILE__).'/');

//全局变量放在这里
$hp_env_vars = array();
require_once(CONF_PATH.'base/core/funcs/hp_common.php');
//数据库配置
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
//数据库配置放入全局变量
$hp_env_vars['db_config'] = $hp_db_configs;
//默认模块
 
$hp_default_module = 'test';
$hp_env_vars['default_module'] = $hp_default_module;


ini_set("session.use_only_cookies",1);
date_default_timezone_set("Asia/Shanghai");

define('URL', $_SERVER['HTTP_HOST'] );
//tpl图片cssjs地址
define('TPL_URL','http://'.URL.'/Yunxin/Tpl/');
//根目录地址
define('ROOT_URL','http://'.URL.'/');  

//上传图片的地址
define('UPLOAD_IMAGE_URL','http://'.URL.'/Public/Uploads/image/');
//上传音频的地址
define('UPLOAD_AUDIO_URL','http://'.URL.'/Public/Uploads/audio/');
//微网站前端文件地址
define('WEIWEB_SUCAI','http://'.URL.'/Yunxin/Tpl/Weiweb/');
//贴吧素材地址
define('TIEBA_SUCAI', 'http://'.URL.'/Yunxin/Tpl/microforum/');
//WM素材地址
define('WM_SUCAI', 'http://'.URL.'/Yunxin/Tpl/WM/');

