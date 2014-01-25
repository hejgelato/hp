<?php
//controller 基类
class hp_controller{

	public function __construct(){
		include_once(INIT_PATH.'tool/smarty/Smarty.class.php');
		$smarty = new Smarty;
		$smarty->template_dir = MODULE_INIT_PATH.'html/';
		$smarty->compile_dir = CONF_PATH.'files/smarty/templates_c/';
		$smarty->config_dir = CONF_PATH.'files/smarty/configs/';
		$smarty->cache_dir = CONF_PATH.'files/smarty/cache/';
		

		

  
		//取消浏览器缓存
		if(env('dev_mode')){
			$smarty->caching  = false;
			$smarty->cache_lifetime = 0; 
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Cache-Control: no-store, must-revalidate");
			header("Pragma: no-cache");

		}
		//开发模式、取消缓存 end
		$this->tpl = $smarty;
		
	}

	//路由,子类可以自由重写
	public function _remap($method){
		if(method_exists($this, $method)){
			$func_before = "a_".$method;
			$func_after  = "z_".$method;

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