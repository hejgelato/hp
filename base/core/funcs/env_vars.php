<?php
//全局变量

function env($key, $value=null){
	global $hp_env_vars;	
	if($value){
		$hp_env_vars[$key] = $value;
	}else{
		if(isset($hp_env_vars[$key])){
			return $hp_env_vars[$key];
		}else{
			return null;
		}
	}
}
 