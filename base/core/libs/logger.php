<?php

class logger{
	static protected function get_dir(){
		return env('log_dir');
	}

	static protected function log_file_name( $name ){
		return self::get_dir().$name.'-'.date("Y-m-d-H").'.log';
		
	}
	
	static public function write($name ,$info){
		$log = array();
		$log['createtime'] = date("Y-m-d H:i:s");
		if(is_array($info)){
			foreach($info as $key=>$var){
				$log[$key] = $var;
			}
		}else{
			$log['info'] = $info; 
		}
		$str = '';
		foreach( $log as $k => $v ){
			$str .= "$k --> $v" . "\n";
		}
		//检查目录是否存在
		 
		if(!is_dir( self::get_dir() )){
			mkdir(self::get_dir(), 0777,true);
		}

		return error_log($str, 3,  self::log_file_name($name));
	}

} 