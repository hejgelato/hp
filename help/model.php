<?php
//模型 注意这里是表模型，一个表对照一个，并不是数据模型，这个模型基本是自动生成的。
$a = new User();

//支持原生pdo 
$a->pdo->xxx

//支持直接执行sql 可跨表
$a->query();


//支持方便查询
$a->where('id',24,'toud',32)->select();
$a->data('a',35,'14',33)->
$a->data(array('a'=>24))
$a->where(array('a'=>23))

$a->where("a = ? and b= ?", array(1,2))
$a->data(array())


$a->table()->where()->order()->limit()->select()
$a->where()->count();
$a->order()->select();

$a->where();
$a->limit()->select()
$a->limit()->select()

//支持方便插入和更新
$a->data($arr)->save()
$a->data($arr)->where("id","4")->update()
$a->data()->update()

$({
	args:'',
	where:'',
	order:'',
});

$(array(
	'args'=>arr,
	'where'=>'',
));

$a->create()->save()

$a->create(ssds)
$a->xx = 's'
$a->save()

//支持方便删除
$a->where()->delete();

//连接  表名等为小全局变量。可修改
$a->table()
$a->connect($mysql_peizhi_name)

//在实际的业务中很多时候需要非常简便的查询
$a->get_by_id()
$a->get_by_name()
$a->get_by_xx()

$a->where()->select()
$a->where()->page(2)
//设置其他小全局变量
//当我把只操作一些默认的数据

//查询条件为小全局变量
$a->where();
$a->select()
$a->update();
$a->where()
$a->where() //多个where默认为and
$a->where()->select()

//order by为小全局变量
$a->order()
$a->where()->select();
$a->where()->select();

//生成sql语句
$a->where->select();
$a->sql();

//检查错误
$a->error();

//换表 data应该能够自动过滤掉不是自己表字段的键 data的参数可以是多维数组
$a->data()->insert();
$b->data($a->where()->select()->insert();

//每一个model建立之初，用代码生成器做成这样
$a->find_eq_xxx() //每一个字段
$a->find_bt_xxx(1,2)
$a->find_gt_xxx(4)
$a->find_lt_xxx(4)
$a->find_neq_xxx()
$a->find_egt_xxx()
$a->find_elt_xxx()
$a->find_like_xxx()

$a->find_eq_pk()


