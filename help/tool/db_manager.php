<?php
require_once("../funcs/common.php");
//读取数据库配置文件
$db_config_file = "../../conf.php";
require_once("$db_config_file");

$act = isset($_GET['act'])?$_GET['act']:null;
if($act == 'list_table'){
	$db = $_GET['db'];
	$conf = $hp_db_configs["$db"];
	if(!$conf) exit("db : $db not there");
	try{
		$dbh = new PDO($conf['dsn'], $conf['user'], $conf['password'] ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));
	}catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
		exit(' Db build connection error!');
	}
	
	//列出表
	$tables = $dbh->query("show tables");
	if(!$tables){
		exit('no table');
	}
	 

}


require_once('tpl/db_manager.html');