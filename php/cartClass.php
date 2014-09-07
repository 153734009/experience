<?php
class Shopcar 
	{ 
		//商品列表 
		public $productList=array(); 
		public function checkProduct($product) 
		{ 
			for($i=0;$i<count($this->productList);$i++ ) 
			{ 
				if($this->productList[$i]['name']==$product['name']) 
					return $i; 
			} 
			return -1; 
		} 
		//添加到购物车 
		public function add($product) 
		{ 
			$i=$this->checkProduct($product); 
			if($i==-1) 
				array_push($this->productList,$product); 
			else 
				$this->productList[$i]['num']+=$product['num']; 
		} 
		//删除 
		public function delete($product) 
		{ 
			$i=$this->checkProduct($product); 
			if($i!=-1) 
				array_splice($this->productList,$i,1); 
		} 
		//返回所有的商品的信息 
		public function show() 
		{ 
			return $this->productList; 
		} 
	}
