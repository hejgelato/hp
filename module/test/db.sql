create table user(
	`user_id` int unsigned not null auto_increment,
	`name` varchar(45) ,
	`age` tinyint unsigned ,
	
	primary key(`user_id`),
	index(`name`)
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8

create table article(
	`article_id` int unsigned not null auto_increment,
	`title` varchar(100),
	`content` text,
	primary key(`article_id`)
	 
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



create table `user-article-author`(
	`user-ariticle-author_id` int unsigned not null auto_increment,
	`user_id` int unsigned,
	`article_id` int unsigned,
	`status` enum('close','open') default 'open',

	primary key(`user-ariticle-author_id`)
	 
)ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8