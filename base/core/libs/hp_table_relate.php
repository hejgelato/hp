<?php
//关联表，用于关联两种数据表的表
class hp_table_relate extends hp_table{
	public function __construct(){
		parent::__construct();	
	}

	//返回关联的表名,默认是本表名的用-分开的部分，子类可以自由重写
	public function first_relate_table(){
		$table_name = $this->table;
		if(!$table_name){
			sys_exit("没有设置表名");
		}
		$names = explode('_', $table_name);
		return $names[0];
	}
	public function second_relate_table(){
		$table_name = $this->table;
		if(!$table_name){
			sys_exit("没有设置表名");
		}
		$names = explode('_', $table_name);
		return $names[1];
	}
	//重写data方法，检查fields的范围为相关联的所有表字段
	public function data( $data=null ){
		$this->data = array();
		if(is_array($data)){
			$temp_args = array();
			$fields = array();
			$first_table = single($this->first_relate_table());
			$second_table = single($this->second_relate_table());
			$fields = array_merge($this->fields, $first_table->fields, $second_table->fields);

			foreach( $data  as $k=>$v){
				if(in_array( $k, $fields,true )){
					$this->data[$k] = '?';
					$temp_args[] = $v;
				}
			}
		}
		if(!$data){
			$this->data = array();
		}
		$this->args_data = $temp_args ;
		return $this;
	}
	//重写where方法，添加联表查询的条件
	public function where($where=null, $args = array()){
		$first_table = single($this->first_relate_table());
		$first_pk = $first_table->pk_name();
		$second_table = single($this->second_relate_table());
		$second_pk = $second_table->pk_name();
		$this_table = $this->table;
		 
		if(!$this_table){
			sys_exit("没有设置表名");
		}
		$relate_where = $this->first_relate_table().".{$first_pk} = {$this_table}.{$first_pk} and {$this_table}.{$second_pk} = ".$this->second_relate_table().".{$second_pk}  ";

		
		if(!$where){
			$this->where = ''.$relate_where;
			$this->args_where = array();
		}elseif(!is_array($where)){
			$where = trim($where);
			$this->where = $where." and ".$relate_where;
			$this->args_where =  $args ;
		}elseif(is_array($where)){
			$str = '';
			$temp_args = array();
			foreach( $where as $k=>$v ){
				$str = $str."and `$k` = ? ";
				$temp_args[] = $v;
			}
			$str = trim($str,'and ');
			$this->where = $str." and ".$relate_where;
			$this->args_where = "  ".$temp_args ;
		}
		return $this;
	}
	//重写select语句，修改select from 后面的部分
	protected function _select($select=null){
		if(!$this->table){
			sys_exit('select with no table');
		}
		if(!$select){
			$select = '*';
		}
		$this->select = $select;
		//查询sql
		if($this->where){
			$where_str = "where ".$this->where; 
		}else{
			$where_str = '';
		}
		if($this->order){
			$order_str = "order by $order";
		}else{
			$order_str = '';
		}
		if($this->limit){
			$limit_str = "limit $limit";
		}else{
			$limit_str = '';
		}
		$first_table = $this->first_relate_table();
		$second_table = $this->second_relate_table();
		$this_table = $this->table;
		$select_from = "{$this_table},{$first_table},{$second_table}";
		$sql = "select ".$this->select." from ".$select_from." $where_str $order_str $limit_str";
		$this->sql = $sql;
		$this->exe_sql( $sql, $this->args_where );
		
	} 


}