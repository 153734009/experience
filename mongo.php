<?php
/*
 +---------------------------------------------------------+
	 1.限定返回的字段
	 $c 表示 集合 collection
 +---------------------------------------------------------+
 */
	$connect = new \MongoClient();
	$db = $connect->eweiwei;
	$collection = $db->category;

	$where = array('gh_id'=>$_SESSION['user']['gh_id']);
	$field = array('_id'=>1,'pid'=>1);
	$order = array("listorder" => -1);
	$limit = 5;

	$cursor = $c->find($where,$field)->sort($order)->limit($limit);;

	//返回的是mongo数据库游标对象
	$arr = iterator_to_array($cursor);
