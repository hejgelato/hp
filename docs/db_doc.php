<?php
/**
表的主键是这样的xx_id ,其中xx是表名。
不同含义的字段不应有相同的名字，比如user表有一个id字段，article表也有一个id字段是不许可的。

关联的表名这样：a-b-relationtype

例如：
文章作者关联表
user-article-author  其中主键为user-article-author_id

**/