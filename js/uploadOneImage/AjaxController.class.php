<?php
namespace Admin\Controller;
use Think\Controller;
class AjaxController extends CommController {
	public function uploadOne(){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     512000 ;// 设置附件上传大小:500K
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =	'/Uploads';
			// 设置附件上传目录:
			$this->where['uid'] = session('uid');
			$gh_id = M('gh')->field('gh_id')->where($this->where)->find();
		$upload->savePath  =     '/'.$gh_id['gh_id'].'/'; 
		$upload->autoSub =	false;
		$info   =   $upload->uploadOne($_FILES['image']);// 上传单个文件 
		if(!$info) {
			$return = array('status'=>0,'info'=>$upload->getError());
		}else{
			$return = array('status'=>1,
					'info'=>$_SESSION['site_url'].$upload->rootPath.$upload->savePath.$info['savename']);
			//项目仅使用本地上传
			//echo $info['savepath'].$info['savename'];
		}
		$return = json_encode($return);
		$return = frequent_unicode_decode_json($return);
		
		//$retrun = preg_replace("/\/",'/',$return);
		echo "<script>parent.imageUploader.uploadCallback(".$return.",false)</script>"; 
	}
}
