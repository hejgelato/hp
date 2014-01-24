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
	//输出信息并停止脚本
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
