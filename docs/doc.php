<?php
/**
//用法
$a = new test();
$a->query("select * from xx where id = ?",array(3));
$a->query("select * from xx where id = 3" );
支持 select update insert delete
