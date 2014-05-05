<?php
/**
  +---------------------------------------------------------------+
 * 17. 压缩文件
 * frequent_zip($path,$savedir,$zipname)
 * 最简调用方法：frequent_zip		还可以进一步整理成表单形式
  +---------------------------------------------------------------+
 * @param string $path		需要压缩的文件[夹]路径
 * @param string $savedir	压缩文件所保存的目录
 * @param string $zipname	压缩文件所保存的目录
  +---------------------------------------------------------------+
 * @return array		zip文件路径
  +---------------------------------------------------------------+
  |
  |	$zip->addEmptyDir('video_api');
  |	$zip->addFile('1.jpg');
  |	$zip->deleteName('1.jpg');
  |	$zip->addFromString('api/index.html','xxxxxxxxxxxx');
  |		$options = array('remove_all_path' => TRUE);
  |	$zip->addGlob('*.*[^*.zip]',GLOB_NOSORT);(匹配待整理)
  | 	//$options = array('add_path' => 'sources/', 'remove_all_path' => TRUE);
  |	//$zip->addGlob('*.*', GLOB_BRACE, $options);
  |	用constant('GLOB_BRACE');查看该常量。该参数128貌似会包含zip自身;
  |	$zip->setArchiveComment('这个是我的注释');
  |	$zip->getArchiveComment();
  |
  +---------------------------------------------------------------+
 */
function frequent_zip($path,$savedir,$zipname){
	$path = $path ? $path : './';
	$savedir = $savedir ? $savedir : './';
	$path = preg_replace('#/+#','/',$path);
	$path = preg_replace('/\/$/', '', $path);		//替换掉结尾的斜杠;可以使用 ##作为定界符;（..	||	&&	等等）
	$savedir = preg_replace('#/+#','/',$savedir);
	$savedir = preg_replace('/\/$/', '', $savedir);		//清空结尾的斜杠
	$savedir = $savedir.'/';				//再加上一个
	//preg_match('/(\w|\.)*$/', $path, $matches,PREG_OFFSET_CAPTURE);//以最后的文件夹名为 zip 名
	preg_match('/\w*$/', $path, $matches,PREG_OFFSET_CAPTURE);//以最后的文件夹名为 zip 名
	//preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, $offset);
		if ($zipname){
			$zipname .= date("Y-m-d").'.zip';
		}elseif($matches[0][0]){
			$zipname = $matches[0][0].date("Y-m-d").".zip";
		}else{
			$zipname = 'www'.date("Y-m-d").'.zip';
		}
	set_time_limit(0);
	$zip = new ZipArchive();
	$zip->open($savedir.$zipname,ZIPARCHIVE::OVERWRITE);
	if (is_file($path)){
		$zip->addFile($path);
		echo($path.date('Y-m-d H-i-s').'<br />');
		return;
	}elseif(is_dir($path)){
	}else{
		echo '请检查路径，找不到该文件或者文件夹';
		return;
	}
	function addToZip($path,&$zip){
		// 引用传递,$zip 指向 ZipArchive对象的一个实例
		// http://www.php.net/manual/zh/language.references.pass.php
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false) {
		if (($file!='.')&&($file!='..')){
			$path_sub = $path.'/'.$file;
			$path_sub = ($path=='.') ? $file : $path.'/'.$file;
			$path_sub = rtrim($path_sub, "/");
			if(is_file($path_sub)){
				echo($path_sub.date('Y-m-d H-i-s').'<br />');
				$zip->addFile($path_sub);
			}elseif(is_dir($path_sub)){
				echo($path_sub.date('Y-m-d H-i-s').'<br />');
				addToZip($path_sub,$zip);
				$zip->addEmptyDir($path_sub);
			}
		}
		}	
	}
	addToZip($path,$zip);
	$zip->close();
}
/**
function ZipStatusString( $status )
{
    switch( (int) $status )
    {
        case ZipArchive::ER_OK           : return 'N No error';
        case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
        case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
        case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
        case ZipArchive::ER_SEEK         : return 'S Seek error';
        case ZipArchive::ER_READ         : return 'S Read error';
        case ZipArchive::ER_WRITE        : return 'S Write error';
        case ZipArchive::ER_CRC          : return 'N CRC error';
        case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
        case ZipArchive::ER_NOENT        : return 'N No such file';
        case ZipArchive::ER_EXISTS       : return 'N File already exists';
        case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
        case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
        case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
        case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
        case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
        case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
        case ZipArchive::ER_EOF          : return 'N Premature EOF';
        case ZipArchive::ER_INVAL        : return 'N Invalid argument';
        case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
        case ZipArchive::ER_INTERNAL     : return 'N Internal error';
        case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
        case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
        case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';
        
        default: return sprintf('Unknown status %s', $status );
    }
}
*/
/**
  +---------------------------------------------------------------+
 * 18. 解压缩文件 --待整理
 * frequent_ezip($zip,$hedef)
  +---------------------------------------------------------------+
 * @param string $zip		压缩包路径
 * @param string $hedef		解压到的路径
  +---------------------------------------------------------------+
 */
function ezip($zip, $hedef = ''){
    $dirname=preg_replace('/.zip/', '', $zip);
    $root = $_SERVER['DOCUMENT_ROOT'].'/zip/';
    $zip = zip_open($root . $zip);
    @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
    while($zip_icerik = zip_read($zip)){
        $zip_dosya = zip_entry_name($zip_icerik);
        if(strpos($zip_dosya, '.')){
            $hedef_yol = $root . $hedef . $dirname.'/'.$zip_dosya;
            @touch($hedef_yol);
            $yeni_dosya = @fopen($hedef_yol, 'w+');
            @fwrite($yeni_dosya, zip_entry_read($zip_icerik));
            @fclose($yeni_dosya); 
        }else{
            @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
        };
    };
}
