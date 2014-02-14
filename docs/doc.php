<?php
/**
//直接查询
$a = new test();
$a->query("select * from xx where id = ?",array($x));
$a->query("select * from xx where id = 3" );
$a->query('insert into xx set a = ?', array($x));
支持 select update insert delete


//查询
$a->where("name = ?", array($name))->select();
$a->where("name = 'mike'")->select();
$a->where("name = 'mike'")->select('name');
//name的值提出来作为一个一维数组
$a->where("name = 'mike'")->select_col('name');
//把id的值放在二维数组的键上
$a->where("name = 'mike'")->select_grp('id');
$a->where("name = 'mike'")->select_one('id');

//插入
$data = array('name'=>'mike','age'=>25);
$a->data($data)->save();

//更新
$data = array('name'=>'mike','age'=>26);
$a->data($data);
$a->where("id =?",array($id))->update();

//删除
$a->where(array('id'=>2));
$a->delete();


//联表查询 支持三个表 表名有约定
$a = new user_article_author();
$b = $a->rel();
$b->where('user.user_id = 1')->select();
