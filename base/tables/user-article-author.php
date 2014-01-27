<?php
class user-article-author extends hp_table{
	//由于是关联表
	public $rel->single('')
	public $table = 'user-article-author';
	public $fields = array(
		'user-article-author_id',
		'user_id',
		'article_id'
	);
}