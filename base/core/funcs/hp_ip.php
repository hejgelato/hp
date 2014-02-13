<?php
// 得到用户的ip
function get_client_ip(){
	if(getenv('HTTP_CLIENT_IP')) { 
	$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR')) { 
	$onlineip = getenv('REMOTE_ADDR');
	} else { 
	$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
	}
	return $onlineip;

}
 
//隐藏ip 变成192.168.*.*
function cover_ip($ip){
	$str = explode(".",$ip);
	$str[2] = '*';
    $str[3] = "*";
    return implode(".",$str);
}