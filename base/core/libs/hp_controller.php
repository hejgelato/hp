<?php
//controller 基类
class hp_controller{
	//路由,子类可以自由重写
	public function _remap($method){
		if(method_exists($this, $method)){
			$func_before = "before_".$method;
			$func_after  = "after_".$method;

			if(method_exists($this, $func_before)){
				$this->$func_before();
			}
			$this->$method();
			if(method_exists($this, $func_after)){
				$this->$func_after();
			}

		}else{
			show_404("控制器中找不到方法:$method");
		}
	}
	//总会被执行的函数
	public function _z(){
		
	}
	public function _a(){
		
	}

}