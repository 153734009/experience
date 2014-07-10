/**
  +-----------------------------------------+
 * 1.复制记录 
  +-----------------------------------------+
*/
格式： insert into table(column,column,...) select column,column,... from table where condition;
	说明:1.不能像使用 一般的insert 有个 values;
	     2.该语句也可以插入多条记录；
例子： inser into qq_user(username,password) select username,password from qq_user where id=1;
-- 复制记录,同时修改某字段内容 
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
  +-----------------------------------------+
  | Field	Type			Null	Key	Default		Extra  
  | log_id	mediumint(8)unsigned	NO	PRI	NULL		auto_incremen
  +-----------------------------------------+
 */
例子：	DESCRIBE TableName/desc tablename
返回：	
   	Field	Type			Null	Key	Default		Extra  
   	log_id	mediumint(8)unsigned	NO	PRI	NULL		auto_incremen
/**
  +-----------------------------------------+
 * 6.ISNULL NULLIF IFNULL
 * mysql 关于null的操作
  +-----------------------------------------+
 */
格式：	ISNULL(expr);
	IFNULL(expr1,expr2);
	NULLIF(expr1,expr2) 
例子：	select ISNULL(`time_end`) as a, IFNULL(`time_end`,'kongkong') as b,NULLIF(99,99) as c,NULLIF(99,100) as d from pa_order limit 2;
返回：	
	+-----------------------------------------+
	| a	b		c	d
	| 1	kongkong	NULL	99	空
	| 0	1383882591	NULL	99	有值
	+-----------------------------------------+

/**
  +-----------------------------------------+
 * 7.创建触发器
  +-----------------------------------------+
  | 注意 delimiter // 之间有个空格
  | 注意 NEW OLD 关键字
  | 多变量赋值， select `column_1`,`column_2` into var_1,var_2 from table limit 1;
  +-----------------------------------------+
 */	
delimiter //
create TRIGGER goods_price_contrast after update on syk_goods for each row
begin
	if NEW.`shop_price` != OLD.`shop_price` then
		
		select `title`, REPLACE(REPLACE(`content`,'#name#',OLD.`goods_name`),'#price#',NEW.`shop_price`) into @msg_title,@msg_content from syk_msg_template where `type`='变价通知' ;
		insert into syk_notification(`title`,`content`,`type`,`status`,`admin_name`,`timestamp`) values (@msg_title,@msg_content,1,1,0,now());
		SELECT LAST_INSERT_ID() into @notification_id;
		insert into syk_notification_details (`notification_id`,`device_token`,`device_type`,`status`,`user_id`,`user_name`,`timestamp`) select @notification_id,`device_token`,`device_type`,1,`user_id`,`user_name`,now() from syk_device_user where `delete`=0 and `user_id` in (select `user_id` from  syk_collect_goods where `is_attention`=1 and `goods_id`=NEW.`goods_id`);		
	end if;
end;
//
drop trigger goods_price_contrast

/**
  +-----------------------------------------+
 * 8.创建存储过程
  +-----------------------------------------+
  | 
  +-----------------------------------------+
 */
delimiter //
create procedure add_notify_log (in id integer)
begin
	insert into syk_notify_log(`n_id`,`receiver`) select id,`user_id` from syk_users where `is_notify`=1;
end;
//
drop procedure add_notify_log

/**
  +-----------------------------------------+
 * 9.优化limit offset
  +-----------------------------------------+
  | MySQL的limit工作原理就是先读取n条记录，然后抛弃前n条，读m条想要的，所以n越大，性能会越差。 
  +-----------------------------------------+
 */
优化前SQL: 
	SELECT * FROM member ORDER BY last_active LIMIT 50,5 
优化后SQL: 
	SELECT * FROM member INNER JOIN (SELECT member_id FROM member ORDER BY last_active LIMIT 50, 5) USING (member_id) 

/**
  +-----------------------------------------+
 * 10.SCOPE_IDENTITY、IDENT_CURRENT 和 @@IDENTITY
  +-----------------------------------------+
  | 
  +-----------------------------------------+
 */
IDENT_CURRENT 不受作用域和会话的限制，而受限于指定的表。
SCOPE_IDENTITY 和 @@IDENTITY 返回在当前会话中的任何表内所生成的最后一个标识值。
但是，SCOPE_IDENTITY 只返回插入到当前作用域中的值；@@IDENTITY 不受限于特定的作用域。



/**
  +-----------------------------------------+
 * 19.使用REGEXP匹配中文(待完善)
  +-----------------------------------------+
  | REGEXP 同义于 RLIKE
  +-----------------------------------------
 */
/*1.匹配 含有非中文字符的记录*/
update qq_shuxingzhi set `title_en`=`title` where `title` REGEXP '[\u4e00-\u9fa5]';

/*2.匹配 全部是中文字符的记录*/
update qq_shuxingzhi set `title_en`=`title` where `title` NOT REGEXP '[\u4e00-\u9fa5]';
update qq_shuxingzhi set `title_en`=`title` where `title` NOT RLIKE '[\u4e00-\u9fa5]';
update qq_shuxingzhi set `title_en`=`title` where not (`title` REGEXP '[\u4e00-\u9fa5]');

/**
  +-----------------------------------------+
 * 20.中文模糊搜索(待整理，待验证)
 *    匹配替换连接，可用于真静态，的页面连接批量替换
  +-----------------------------------------+
  | 在MySQL中，进行中文排序和查找的时候，对汉字的排序和查找结果是错误的。
  | 原因是：MySQL在查询字符串时是大小写不敏感的，在编绎MySQL时一般以ISO-8859字符集作为默认的字符集，因此在比较过程中中文编码字符大小写转换造成了这种现象。
  |
  +-----------------------------------------+
 */
SELECT * FROM table WHERE locate(field,'李') > 0;
SELECT * FROM TABLE WHERE FIELDS LIKE BINARY '%FIND%';
update gh_menu set url = REPLACE(`url`,'pth.eweiwei.com','www.pth-express.com')

/**
  +-----------------------------------------+
 * 20.left join + where(待整理，待验证)
  +-----------------------------------------+
  | 在MySQL中，进行中文排序和查找的时候，对汉字的排序和查找结果是错误的。
  | 原因是：MySQL在查询字符串时是大小写不敏感的，在编绎MySQL时一般以ISO-8859字符集作为默认的字符集，因此在比较过程中中文编码字符大小写转换造成了这种现象。
  |
  +-----------------------------------------+
 */
SELECT wz020_sites_article.*,wz020_sites_category.name as category FROM `wz020_sites_article` LEFT JOIN wz020_sites_category on wz020_sites_article.category=wz020_sites_category.id WHERE ( `category` IN ('9','makeItems2') )


/*待整理*/select `goods_id` as id,`goods_name` as name,`shop_price` as price,IF(`promote_price`=0,`shop_price`,`promote_price`) as special_price,TRUNCATE(IF(`promote_price`=0,`shop_price`,`promote_price`)/`shop_price`,2) as rebate, `goods_img` as filename,`brand_id`,`cat_id`,`is_real` as recommend,`is_new` as status_1,`is_hot` as status_2,IF(`goods_number`>0,0,1) as status_4,`click_count` as ob_1 from ".$prefix."goods where `is_on_sale`=1 and `is_alone_sale`=1 and `is_delete`=0 ;
/*left join*/ select g.*,og.sales from (select `goods_id` as id,`goods_name` as name,`shop_price` as price,IF(`promote_price`=0,`shop_price`,`promote_price`) as special_price,TRUNCATE(IF(`promote_price`=0,`shop_price`,`promote_price`)/`shop_price`,2) as rebate, `goods_img` as filename,`brand_id`,`cat_id`,`is_real` as recommend,`is_new` as status_1,`is_hot` as status_2,IF(`goods_number`>0,0,1) as status_4,`click_count` as ob_1 from syk_goods where `is_on_sale`=1 and `is_alone_sale`=1 and `is_delete`=0 ) as g left join (select SUM(`goods_number`) as sales,`goods_id` as id from syk_order_goods group by `goods_id`) as og on g.id=og.id
/*right join*/ select IFNULL(s.sales,0) as sales,g.* from (select SUM(`goods_number`) as sales,`goods_id` as id from syk_order_goods group by `goods_id`) as s right join (select `goods_id` as id,`goods_name` as name,`shop_price` as price,IF(`promote_price`=0,`shop_price`,`promote_price`) as special_price,TRUNCATE(IF(`promote_price`=0,`shop_price`,`promote_price`)/`shop_price`,2) as rebate, `goods_img` as filename,`brand_id`,`cat_id`,`is_real` as recommend,`is_new` as status_1,`is_hot` as status_2,IF(`goods_number`>0,0,1) as status_4,`click_count` as ob_1 from syk_goods where `is_on_sale`=1 and `is_alone_sale`=1 and `is_delete`=0 ) as g on g.id=s.id
insert into syk_notification_details (`notification_id`,`device_token`,`device_type`,`user_id`,`user_name`,`timestamp`) select 10 as notification_id, `device_token`,`device_type`,`user_id`,`user_name`,now() as timestamp from syk_device_user where `user_id` in (select `user_id` from syk_collect_goods where `goods_id`='100' and `is_attention`=1)
-----------------------------------------------
CREATE DEFINER=`root`@`localhost` PROCEDURE `test_multi_sets`()
   DETERMINISTIC
begin
       select user() as first_col;
       select user() as first_col, now() as second_col;
       select user() as first_col, now() as second_col, now() as third_col;
       end
--------------
mysql> delimiter |
mysql>
mysql> CREATE TRIGGER tr_rwxdfbb_bi BEFORE INSERT ON t_rwxdfbb
    ->   FOR EACH ROW BEGIN
    ->     if new.name is null then
    ->          set new.name=new.id;
    ->     end if;
    ->   END;
    -> |
----------------------------------------
/**
  +-----------------------------------------+
 * 8.进阶基础操作--待整理
  +-----------------------------------------+
 */
ALTER TABLE  `syk_login` ADD  `user_id` INT( 11 ) NULL ,ADD INDEX (  `user_id` )
alter table 表名 drop 列名;
alter table 表名 add 列名 column specifications and constraints;
alter table 表名 add 列名 column specifications and constraints first;
alter table 表名 add 列名 column specifications and constraints after 列名;
select COLUMN_NAME from information_schema.columns where table_name='w_sites'

/**
  +-----------------------------------------+
 * 21.自增值
  +-----------------------------------------+
 */
$auto_increment = M()->query('SHOW TABLE STATUS where name="w_sites_category"');
$auto_increment = $auto_increment[0]['Auto_increment'];

/**
  +-----------------------------------------+
 * 21.每个分类一条记录
		这里巧妙的运用了count()函数会返回一条记录，而且是相同类型记录中的第一条记录的特点
  +-----------------------------------------+
 */
select *,count(distinct `category`) from `w_sites_product` group by `category`

/**
  +-----------------------------------------+
 * 22.最新的5条记录，但是栏目必须不同
  +-----------------------------------------+
 */
SELECT  * FROM `w_sites_product` main WHERE	NOT EXISTS( SELECT 1 FROM  `w_sites_product`  sub  WHERE  main.`category` = sub.`category` AND  main.`createtime` < sub.`createtime`)	ORDER BY  `createtime` DESC LIMIT 5



/**
  +---------------------------------------------------------------+
  * 普及常识
  *	
  * 	1.字符连结 select CONCAT('http://hosting328.gotoip1.com/goods.php?id=',`id`) as href from table;字符连结
  * 	2.ROW_COUNT()函数的确只对UPDATE，DELETE，INSERT操作起作用，而且是这些操作发生了实际影响时才会记录数据。
  *			mysql_affected_rows();select ROW_COUNT()
  *		3.alter table ad_attribute modify `title_en` varchar(255) after `title`;	--移动列的顺序;需要指出字段类型 
  *		4.TRUNCATE   TABLE
  *		5.$r = M('')->query("SHOW TABLE STATUS LIKE  'w_sites_category%'");	$r[0]['Auto_increment'];
  *		  SHOW TABLE STATUS where name='w_sites_category'"
  * 
  * 
  * 
  *
  +---------------------------------------------------------------+
 */


/**
目录	Table of Contents ¶
	1.  复制记录------------------------------------------------------------------------  003
	2.  同一字段同时满足多条件查询（haveing）-------------------------------------------  020
	3.  表结构查询----------------------------------------------------------------------  064
	4.  ISNULL NULLIF IFNULL------------------------------------------------------------  076
	5.  创建触发器  存储过程------------------------------------------------------------  093
	9.  优化limit offset----------------------------------------------------------------  131
	10.	SCOPE_IDENTITY、IDENT_CURRENT 和 @@IDENTITY-------------------------------------  141
	21. 每个分类一条记录----------------------------------------------------------------  241
	22.	最新的5条记录，但是栏目必须不同---------------------------------------------------  246
	9.  优化limit offset----------------------------------------------------------------  131
	9.  优化limit offset----------------------------------------------------------------  131














