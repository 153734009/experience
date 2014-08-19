<?php
/**
  +-----------------------------------------+
 * 1.删除一个键 
  +-----------------------------------------+
*/
$c->update($criteria, array('$unset'=>array('attr'=>array())));

/**
  +-----------------------------------------+
 * 2.删除数组里的一个数组
  +-----------------------------------------+
*/
	
	$r = $c->update(
		array('_id'=>new \MongoId($_GET['_id'])),
		array('$pull'=>array('attr'=>array('id'=>'538442480f0f203801000035')))
	);

/**
  +-----------------------------------------+
 * 3.获取object的id 表示
  +-----------------------------------------+
*/
	$mongoid->{'$id'} 
	

/*
 +---------------------------------------------------------+
	 1.限定返回的字段
	 $c 表示 集合 collection
	 object id 比较特殊一些，需要 '_id'=>0
 +---------------------------------------------------------+
 */
	$connect = new \MongoClient();
	$db = $connect->eweiwei;
	$collection = $db->category;

	$where = array('gh_id'=>$_SESSION['user']['gh_id']);
	$field = array('_id'=>1,'pid'=>1, '_id'=>0);
	$order = array("listorder" => -1);
	$limit = 5;

	$cursor = $c->find($where,$field)->sort($order)->limit($limit);;

	//返回的是mongo数据库游标对象
	$arr = iterator_to_array($cursor);


/**
 * 2. 数据库 集合列表
 */
$collections = $m->selectDB("eweiwei")->getCollectionNames();
