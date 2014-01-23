<?php
define('CORE_FUNC_PATH',dirname(__FILE__).'/');
include_once(CORE_FUNC_PATH.'env_vars.php');

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

function sys_exit($msg=null){
	exit($msg);
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
