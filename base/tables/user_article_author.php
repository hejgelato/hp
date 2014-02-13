<?php
class user_article_author extends hp_table{
	public $table = 'user_article_author';
	public $fields = array(
		'user_article_author_id',
		'user_id',
		'article_id',
		'status'
	);

	public function rel(){
		return single('hp_table_relate');
	}
}