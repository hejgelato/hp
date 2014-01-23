<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new test();
		$b = new test();
		$c = new test();
		 
		//$this->test_where();
		$this->test_save();
	}

	public function test_where(){
		$a = new test();
		$a->where('a = ?', array('24'));
		echo $a->where;
		next_line();
		dump( $a->args_where );
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