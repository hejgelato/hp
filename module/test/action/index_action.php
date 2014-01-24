<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new test();
		$b = new test();
		$c = new test();
		 
		 $this->test_select();
		// $this->test_save();
		//$this->test_update();
		//$this->test_delete();
		//$this->test_query();
	}

	public function test_select(){
		$a = new test();
		$a->where('name = ?', array('mike'));
		echo $a->where;
		 
		//dump( $a->select() );
		//dump($a->select_one());
		dump($a->select_col('xname'));
		dump($a->select_grp('name'));
		dump($a->select_grp('id'));
	}

	public function test_save(){
		$a = new test();
		$data = array('name'=>'mike','age'=>25);
		$a->data($data);
		dump($a->data);
		dump($a->args_data);
		dump( $a->save());
	}

	public function test_update(){
		$a = new test();
		$data = array('name'=>'mike','age'=>26);
		$a->data($data);
		dump($a->where("2 =?",array(2))->update());

		$data = array('name'=>'blue','age'=>2);
		dump($a->data($data)->where("1=1")->update());
	}
	public function test_delete(){
		$a = new test();
		$a->where(array('id'=>2));
		dump($a->delete());
		dump($a->sql);
	}

	public function test_query(){
		$a = new test();
		 dump($a->query("select * from test where id = ?",array(3)));
		 dump($a->query("update test set name= ? where id =?", array('g',3)));
		 dump($a->query("insert into test set name= ? ,age =?", array('gb',13)));
		dump($a->query("delete from test where age =?", array( 2)));
		
	}
	public function _z(){
		echo "<br>"; echo "ends";
	}
}