<?php


//实例化并且单例
function single($class){
	return factory::make_one_obj($class);
}

//看sql是不是以某开头
function sql_start( $sql ){
	$str = trim($sql,'()');
	$needles = func_get_args();
	array_shift( $needles );
	foreach( $needles as $var ){
		if(strpos(trim(strtolower($sql)), strtolower($var)) === 0 ){
			return true;
		}
	}
	return false;
}
//数组输出字符串
function arr_readable($arr){
	if(!$arr) return '';
	$str = '';
	foreach($arr as $k=>$v){
		$str.= " $k : $v".'|';
	}
	return $str;
} 

//输出信息并停止脚本 在开发模式下输出出错信息，打开日志的情况下记录日志
function sys_exit($msg=''){
	header('Content-type:text/html;charset=utf8');
	
	$log = array();
	$log['exit_msg'] = $msg;
	$log['hp_path'] = env('hp_path');
	 
	if(isset($_SESSION)){
		$log['session_str'] = arr_readable($_SESSION);
	}
	if(isset($_SERVER['HTTP_USER_AGENT'])){
		$log['agent'] = $_SERVER['HTTP_USER_AGENT'];
	}
	if(isset($_SERVER['HTTP_REFERER'])){
		$log['ref'] = $_SERVER['HTTP_REFERER'];
	}
	$info =  debug_backtrace();
	if($info){
		foreach($info as $var){
			$log[] = $var['file'].':'.$var['line'];
		}
	}
	echo "页面发生了错误：";
	if(env('dev_mode')){
		dump( $log );
	}else{
		echo '查看详细信息,请设置dev_mode=true';
	}
	//记录日志
	if(env('log_user_runtime')){
		logger::write('php_sys_exit_log',$log);
	}
	exit();
}
//产生一个警告，在开发模式下输出警告信息，打开日志的情况下记录日志
function sys_warn($msg=''){
	header('Content-type:text/html;charset=utf8');
	
	$log = array();
	$log['warn_msg'] = $msg;
	$log['hp_path'] = env('hp_path');
	 
	if(isset($_SESSION)){
		$log['session_str'] = arr_readable($_SESSION);
	}
	if(isset($_SERVER['HTTP_USER_AGENT'])){
		$log['agent'] = $_SERVER['HTTP_USER_AGENT'];
	}
	if(isset($_SERVER['HTTP_REFERER'])){
		$log['ref'] = $_SERVER['HTTP_REFERER'];
	}
	$info =  debug_backtrace();
	if($info){
		foreach($info as $var){
			$log[] = $var['file'].':'.$var['line'];
		}
	}
	 
	if(env('dev_mode')){
		echo "系统产生了一个警告";
		dump( $log );
	}  
	//记录日志
	if(env('log_user_runtime')){
		logger::write('php_sys_warn_log',$log);
	}
	 
}

//utf8 的输出错误并退出
function cn_exit($msg){
	header('Content-type:text/html;charset=utf8');
	exit($msg);
}
//调转到404页面
function show_404($msg=null){
	if(!env('dev_mode')){
		header('Location:'.BASE_HTML_URL.'404.html');
	}else{
		cn_exit('404-'.$msg);
	}
}

function next_line(){
	echo "<br/>";
}

//格式化的输出
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
//过滤sql特殊字符
function daddslashes($string,$strip = FALSE){
	if(!$string) return $string;
    if(is_array($string)) {   
		foreach($string as $key => $val) {   
			$string[$key] = daddslashes($val, $strip);   
		}   
	}else{   
		$string = addslashes($strip ? stripslashes($string) : $string);   
	}   
    return $string;   
} 
//trim
function dtrim( $string ){
	if(!$string) return $string;
	if(is_array($string)) {   
		foreach($string as $key => $val) {   
			$string[$key] = dtrim($val);   
		}   
	}else{   
		$string = trim($string);   
	}   
    return $string;
}