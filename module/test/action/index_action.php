<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new test();
		$b = new test();
		$c = new test();
		//echo $c->where("username = 'a'")->where("passwd = 'pa'")->where;
		echo $c->where("username = 'a'")->where(array('passwd'=>'pa'))->where;
		echo $c->where("")
	} 
}