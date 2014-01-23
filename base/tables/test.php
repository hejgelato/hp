<?php
class test extends hp_table{
	 
	public $db_conf = 'default';             //数据库配置
	public $table = 'test';
	public $fields = array(
		'id',
		'name','age'
	);                  
	 
	
	public function __construct(){
		parent::__construct();
	}



}