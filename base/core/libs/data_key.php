<?php
class data_key{
	//对一个数据生成一个键值标记 只针对string ,数字,对象和数组
	static public function data_get_key( $data ){
		if(!$data){
			return null;
		}elseif( is_array( $data )){
			$str = json_encode($data);
			return md5($str.'abc');
		}elseif(is_object($data)){
			return spl_object_hash($data);
		}else{
			return md5($data.'abcd');
		}
	}
}