<?php
class index_action extends hp_controller{
	
	public function index(){
		$a = new test();
		$b = new test();
		$c = new test();
		 dump($_GET);
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
		dump($a->select_col('name'));
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
	public function index_z(){
		echo "<br>";echo "index ends";
	}
	public function _z(){
		echo "<br>"; echo "ends";
		$this->tpl->display('readme.html');
	}

	public function test_create(){
		$a = new test();
		 
 
		$a->where('id=?',array(1))->data(array('name'=>25))->update();
		 
	}

	public function test_rel(){
		//$a = new user();
		//$data1 = array('name'=>'jack','age'=>24);
		//$a->data($data1)->save();

		//$a = new article();
		//$a->data(array('title'=>'art1','content'=>'test'))->save();
		//$a = new user_article_author();
		//$a->data(array('user_id'=>1,'article_id'=>1,'status'=>'open'))->save();
		$a = new user_article_author();
		$b = $a->rel();
		dump ($b->where('user.user_id = 1')->select());
		dump($b->sql);
	}
	 
}