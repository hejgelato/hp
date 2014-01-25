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
	public $query_return = null;            //执行sql语句返回的结果
	public $query_data_set = null;          //执行sql语句返回的结果集
	public $query_affected_count = null;    //执行sql语句影响到的行数
	public $during_transaction  = false;    //是否在事务中

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
		$this->query_return = null;
		$this->query_data_set = null;
		$this->query_affected_count = null;
		$this->during_transaction = false;
	}

	public function __construct(){
		//生成一个数据库连接
		$conf = env('db_config');
		$conf = $conf[$this->db_conf];
		$this->pdo = self::get_pdo( $conf );		
	}
	//生成一个数据库pdo连接
	static public function get_pdo( $conf ){ 
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
	//通用的执行sql的函数
	public function query( $sql, $args = array()){
		$sql = trim($sql);
		if(sql_start($sql, 'update','delete')){
			$this->exe_sql($sql, $args);
			if($this->query_return){
				return $this->query_affected_count ;
			}else{
				return $this->query_return;
			}
		}elseif(sql_start($sql, 'insert')){  
			$this->exe_sql($sql, $args);
			if($this->query_return){
				return $this->pdo->lastInsertId();
			}else{
				return $this->query_return;
			}
		}elseif(sql_start($sql, 'select')){
			$this->exe_sql($sql, $args);
			if($this->query_return){
				return  $this->query_data_set;
			}else{
				//查询不到数据的时候返回空白数组
				return array();
			}
		}else{
			sys_exit("can only query() select, insert ,update, delete.");
		}
	}
	//执行sql
	protected function exe_sql($sql, $args = array()) {
		$this->query_return = null;
		$this->query_data_set = null;
		$this->query_affected_count = null;
		
		$stat = $this->pdo->prepare ( $sql );
		$result = $stat->execute( $args );
		$this->query_return = $result;
		if($result){
			$this->pdo_statement = $stat;
			$this->query_data_set = $stat->fetchAll(PDO::FETCH_ASSOC);
			$this->query_affected_count = $stat->rowCount();
		}else{
			if(env('dev_mode')){
				
				dump($stat->errorCode());
				dump($stat->errorInfo());
			
				$this->show_error();
				sys_warn("sql语句执行返回了false");
			}
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
				if(in_array( $k, $this->fields,true )){
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
	//检查字段名是不是合法的
	protected function is_valid_col($col){
		if(in_array($col, $this->fields,true)){
			return true;
		}else{
			return false;
		}
	}

	//返回的二d数据中键名不再是默认的0123，指定键名
	public function select_grp($col){
		if($col){
			if(!$this->is_valid_col($col)){
				sys_warn(" table $this->table does not have colomn: $col !");
				return false;
			}
			$this->_select();
			if($this->query_return){
				$data_set = $this->query_data_set;
				$return = array();
				foreach($data_set as $v){
					$return[$v[$col]] = $v;
				}
				return $return;
			}else{
				//查询不到数据的时候返回空白数组
				return array();
			}
		}else{
			return $this->select();
		}
	}

	//只返回指定的列名的数据 一d数据
	public function select_col($col){
		if(!$col){
			return array();
		}
		if(!$this->is_valid_col($col)){
			sys_warn(" table $this->table does not have colomn: $col !");
			return false;
		}
		$this->_select();
		$data = $this->query_data_set;
		$return = array();
		if($data){
			foreach($data as $v){
				$return[] = $v[$col];
			}
		}
		return $return;
		 
	}
	//返回一条数据，结果是一d数据
	public function select_one( $select=null ){
		$this->_select($select);
		if($this->query_return){
			$data_set = $this->query_data_set;
			return $data_set[0];
		}else{
			//查询不到数据的时候返回空白数组
			return array();
		}
	}
	//同select_one
	public function select_first($select=null){
		return $this->select_one($select);
	}
	//只执行select语句
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
		$sql = "select ".$this->select." from ".$this->table." $where_str $order_str $limit_str";
		$this->sql = $sql;
		$this->exe_sql( $sql, $this->args_where );
		
	}
	//select方法，查询sql
	public function select($select=null){
		$this->_select($select);
		if($this->query_return){
			$data_set = $this->query_data_set;
			return $data_set;
		}else{
			$this->query_return;
		}
	}
 
	public function count($count='*'){
		$count = $this->select_one(" count( $count ) as c ");
		if($count){
			return $count['c'];
		}else{
			return 0;
		}
	}
	 
	//save函数 执行insert
	public function save(){
		//检查一些必要条件
		if(!$this->table){
			sys_exit('save with no table');
		}
		if(!$this->data){
			sys_warn('save with no data,function return false');
			return false;
		}

		$data_str = $this->parse_data( $this->data );
		$sql = "insert into ".$this->table." set $data_str";  
		$this->exe_sql( $sql, $this->args_data );
		if($this->query_return){
			return $this->pdo->lastInsertId();
		}else{
			return $this->query_return;
		}
	}
	//update函数 执行update
	public function update(){
		if(!$this->table){
			sys_exit('update with no table');
		}
		if(!$this->data){
			sys_warn('update with no data,function return false');
			return false;
		}
		$data_str = $this->parse_data( $this->data );
		//查询sql
		if(!$this->where){
			sys_warn("update db without where statement, be careful!"); 
		} 
		$sql = "update ".$this->table." set $data_str where ".$this->where;
		$this->sql = $sql;
		$this->exe_sql( $sql, array_merge($this->args_data,$this->args_where) );
		if($this->query_return){
			return $this->query_affected_count ;
		}else{
			return $this->query_return;
		}
	}
	//delete函数 , 执行delete
	public function delete(){
		if(!$this->table){
			sys_exit('delete with no table');
		}
		if(!$this->where){
			sys_warn("delete db without where statement, be careful!"); 
		}
		$sql = "delete from ".$this->table."  where ".$this->where;
		 
		$this->sql = $sql;
		$this->exe_sql( $sql, $this->args_where );
		if($this->query_return){
			return $this->query_affected_count ;
		}else{
			return $this->query_return;
		}
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
	
	//show_error()
	public function show_error(){
		dump( $this->pdo->errorCode());
		dump( $this->pdo->errorInfo());
	}

	//事wu支持 
	public function begin(){
		if(!$this->during_transaction ){
			$this->pdo->beginTransaction();
			$this->during_transaction = true;
		}

	}
	public function commit(){
		if( $this->during_transaction ){
			$this->pdo->commit();
			$this->during_transaction = false;
		}
	}
	public function rollback(){
		if( $this->during_transaction ){
			$this->pdo->rollBack();
			$this->during_transaction = false;
		}
	}
}