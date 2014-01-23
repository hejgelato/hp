<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new test();
		$b = new test();
		$c = new test();
		 
		$this->test_select();
		//$this->test_save();
	}

	public function test_select(){
		$a = new test();
		$a->where('name = ?', array('mike'));
		echo $a->where;
		 
		dump( $a->args_where );
		dump($a->select());
	}

	public function test_save(){
		$a = new test();
		$data = array('name'=>'mike','age'=>25);
		$a->data($data);
		dump($a->data);
		dump($a->args_data);
		dump( $a->save());
	}
}