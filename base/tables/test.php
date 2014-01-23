<?php
class test extends hp_table{
	 
	public $db_conf = 'default';             //数据库配置
	
	public $fields = array(
		'id',
		'a','b'
	);                  
	 
	
	public function __construct(){
		parent::__construct();
	}



}