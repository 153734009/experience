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
	