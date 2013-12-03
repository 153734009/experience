/**
  +-----------------------------------------+
 * 1.复制记录 
  +-----------------------------------------+
*/
格式： insert into table(column,column,...) select column,column,... from table where condition;
	说明:1.不能像使用 一般的insert 有个 values;
	     2.该语句也可以插入多条记录；
例子： inser into qq_user(username,password) select username,password from qq_user where id=1;
/**
 * 复制记录,同时修改某字段内容 
*/
格式： insert into table(column,column,...) select column,"xxxxxx",... from table where condition;
	说明:1.固定值使用 "xxx";
	     2.否则 请使用函数；
例子： inser into qq_user(username,password) select username,Month(getdate()) from qq_user where id=1;

/**
  +-----------------------------------------+
 * 2.同一字段同时满足多条件查询
 * 应用场景：
 * id	product_id	attr	attr_value
  +-----------------------------------------+
*/
格式：	select column from table where (condition_1) or (condition_2)...or(condition_$i) group by column having count(column)=$i;
	说明:1. $i是你的条件数
	     2. having
例子：	select `product_id` from table where (`attr`='' and `attr_value`='') or (`attr`='' and `attr_value`='') group by `product_id` having count(*)=2;

/**
  +-----------------------------------------+
 * 3.创建视图 
  +-----------------------------------------+
 */
格式：	CREATE VIEW view_name AS SELECT column_name(s) FROM table_name WHERE condition;
例子：	create view good_attr_value_praise as select `goods_id`,`attr_value`,sum(`praise_value`) as praise_value,count(`praise_value`) as praise_amount,ROUND(sum(`praise_value`)/count(`praise_value`),1) as pv from ".$prefix."goods_attr_praise group by `attr_value`;


/**
  +-----------------------------------------+
 * 4.临时表（内存表）
  +-----------------------------------------+
 */
格式：	CREATE TABLE (
		column datetype,
		...
		)
例子：	create temporary table if not exists tablename(
		id int(11) primary key auto_increment not null,
		goods_id mediumint(8),
		nickname varchar(60),
		title text,
		praise_value tinyint(1),
		summary text,
		timestamp int(10)
		)
	mysql_query ($sql);	/*don't forgot query*/
	insert into tablename (`goods_id`,`nickname`,`title`,`praise_value`,`summary`,`timestamp`) select `id_value`,`user_name`,`comment_title`,`comment_rank`,`content`,`add_time` from  ".$prefix."comment where `id_value`=100;
	select ROUND(sum(`praise_value`)/count(`id`),1) as value,count(`id`) as number from tablename;


/**
  +-----------------------------------------+
 * 5.表结构查询
 * DESCRIBE TableName/desc tablename
  +-----------------------------------------+
  | Field	Type			Null	Key	Default		Extra  
  | log_id	mediumint(8)unsigned	NO	PRI	NULL		auto_incremen
  +-----------------------------------------+
 */


/**
  +---------------------------------------------------------------+
  * 普及常识
  *	
  * 	1.字符连结 select CONCAT('http://hosting328.gotoip1.com/goods.php?id=',`id`) as hre from table;字符连结
  * 
  * 
  * 
  *
  * 
  * 
  * 
  *
  +---------------------------------------------------------------+
 *
