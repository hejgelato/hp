<?php
//数据表操作类的基类
class hp_table{
	static $con = null;                //用于存放一个数据库连接,检查单例
	public $db_conf = 'default';             //数据库配置
	
	public $fields = array();               //表对应的字段
	public $pdo = null;                     //对象的数据库连接
	public $table = '';                     //对应的表
	public $where = '';                     //查询条件
	public $args_where  = array();			//sql语句查询条件用的代替？的参数
	public $args_data   = array();          //sql语句插入或者更新部分用的代替?的参数
	public $data = array();                 //数据
	public $order = '';                     //排序语句
	public $limit = '';                     //limit 语句
	public $select = '';                    //select语句
	public $sql = '';                       //sql语句
	public $last_insert_id = null;      
	public $query_return = null;            //执行sql语句返回的结果

	//初始化，把对象的部分状态初始化，比如单例模式中，虽然不重新new一个对象，但是应该清理之前的
	//使用者留下的痕迹 被单例函数使用
	public function _renew(){
		$this->where = '';    
		$this->args_where = array();
		$this->args_data = array();
		$this->data = array();                      
		$this->order = '';                      
		$this->limit = '';                     
		$this->select = '';                    
		$this->sql = ''; 
		$this->last_insert_id = null;
		$this->query_return = null;
	}

	public function __construct(){
		//生成一个数据库连接
		$conf = env('db_config');
		$conf = $conf[$this->db_conf];
		$this->pdo = self::get_pdo( $conf );		
	}
	//生成一个数据库pdo连接
	static public function  get_pdo( $conf ){ 
		$mark = data_key::data_get_key($conf);
		if(!$mark){
			sys_exit('db config key empty!');
		}
		if( !isset( self::$con[$mark] )){  
			try {
				$dbh = new PDO($conf['dsn'], $conf['user'], $conf['password'] ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';",PDO::ATTR_PERSISTENT => true));
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
				sys_exit(' Db build connection error!');
			}
			if($dbh){
				self::$con[$mark] = $dbh;
			}else{
				sys_exit(' Db build connection error!');
			} 
		}
		return self::$con[$mark];
	}
	//执行sql
	public function exe_sql($sql, $args = array()) {
		$stat = $this->pdo->prepare ( $sql );
		$result = $stat->execute ( $args );
		$this->query_result = $result;
		if($result){
			$rs = $stat->fetchAll();
			return $rs;
		}else{
			$this->show_error();
			return $result;
		}
	}

	//table方法，设置表名
	public function table( $table ){
		$this->table = trim($table);
		return $this;
	}
	//data方法，设置数据 参数只能是一d数组
	public function data( $data=null ){
		$this->data = array();
		if(is_array($data)){
			$temp_args = array();
			foreach( $data  as $k=>$v){
				if(in_array( $k, $this->fields )){
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


	//where方法，设置查询条件
	public function where($where=null, $args = array()){
		if(!$where){
			$this->where = '';
			$this->args_where = array();
		}elseif(!is_array($where)){
			$where = trim($where);
			$this->where = $where;
			$this->args_where =  $args ;
		}elseif(is_array($where)){
			$str = '';
			$temp_args = array();
			foreach( $where as $k=>$v ){
				$str = $str."and `$k` = ? ";
				$temp_args[] = $v;
			}
			$str = trim($str,'and ');
			$this->where = $str;
			$this->args_where = $temp_args ;
		}
		return $this;
	}
	//order方法，设置排序
	public function order($order=null){
		if(!$order){
			$this->order = '';
		}else{
			$this->order = trim($order);
		}
		return $this;
	}
	//limit方法,设置limit
	public function limit($offset, $limit=null){
		$offset = (int)$offset;
		if(!$limit){
			$this->limit = "limit $offset";
		}else{
			$limit = (int)$limit;
			$this->limit = "limit $offset, $limit";
		}
		return $this;
	}
	//select方法，查询sql
	public function select($select=null){
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
		$sql = "select ".$this->select." from ".$this->table." $where_str $order_str $limit_str";
		$this->sql = $sql;
		return $this->exe_sql( $sql, $this->args_where );
	}
	public function count($count='*'){
		$count = $this->select("count( $count ) as c ");
		if($count){
			return $count[0]['c'];
		}else{
			return 0;
		}
	}
	//查询结果转为数组
	protected function obj_array($obj){
		if($obj === false) return false;
		$arr = array();
		if($obj){
			foreach($obj as $k=>$v){
				$arr[$k] =  self::strip_array_html($v) ;
			}
		}
		return $arr;
	}
	//结果数组过滤html
	protected function strip_array_html($arr){
		$new = array();
		foreach($arr as  $k=>$v){
			if(!is_numeric ($k)){
				$new[$k] = htmlspecialchars($v);
			}
		}
		return $new;
	}
	//save函数 执行insert
	public function save(){
		$data_str = $this->parse_data( $this->data );
		$sql = "insert ".$this->table." set $data_str";
		$this->sql = $sql;
		$this->exe_sql( $sql, $this->args_data );
		if($this->query_return){
			$id =  $this->pdo->query('select last_insert_id() as i');
			$id = self::obj_array($id); 
			return $id[0]['i'];
		}else{
			return $this->query_return;
		}
	}
	//update函数 执行update
	public function update(){
		$data_str = $this->parse_data( $this->data );
		//查询sql
		if(!$this->where){
			exit("update db without where, be careful! try to use query(...)"); 
		} 

		$sql = "update ".$this->table." set $data_str where ".$this->where;
		$this->sql = $sql;
		$this->exe_sql( $sql, array_merge($this->args_data,$this->args_where) );
		return $this->query_return;
	}
	//delete函数 , 执行delete
	public function delete(){
		if(!$this->where){
			exit("delete db without where, be careful! try to use query(...)"); 
		}
		$sql = "delete from ".$this->table."  where ".$this->where;
		$sql = mysql_real_escape_string($sql);
		$this->sql = $sql;
		return $this->pdo->exec( $sql );

	}

	//数据组成字符串
	protected function parse_data($arr){
        $str = '';
        foreach($arr as $k=>$v){
			$kk = strtolower($k);
            $str .= "`$kk` = $v ,";
        }
        return rtrim($str, ',');
    }
	//create 方法，从表单或者数据库接受数据到data
	public function create(){
		
	}
	//show_error()
	public function show_error(){
		echo $this->pdo->errorCode();
		echo $this->pdo->errorInfo();
	}
}