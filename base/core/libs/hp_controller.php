<?php
//controller 基类
class hp_controller{
	//路由,子类可以自由重写
	public function _remap($method){
		if(method_exists($this, $method)){
			$this->$method();
		}else{
			$current_controller = env('current_controller');
			exit("controller $current_controller dont have method: $method");
		}
	}
	//总会被执行的函数
	public function _z(){
		
	}
	public function _a(){
		
	}

}