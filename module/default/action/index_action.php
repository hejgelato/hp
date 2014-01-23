<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new hp_table();
		$b = new hp_table();
		$c = new hp_table();
		//echo $c->where("username = 'a'")->where("passwd = 'pa'")->where;
		echo $c->where("username = 'a'")->where(array('passwd'=>'pa'))->where;
	} 
}