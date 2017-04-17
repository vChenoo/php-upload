<?php
//$_FILES
//print_r($_FILES);
$filename=$_FILES['myFile']['name'];
$type=$_FILES['myFile']['type'];
$tmp_name=$_FILES['myFile']['tmp_name'];
$error=$_FILES['myFile']['error'];
$size=$_FILES['myFile']['size'];
$allowExt=array("gif","jpeg","jpg","png","wbmp","doc");
$maxSize=104876;//2M

if($error==UPLOAD_ERR_OK){
	//判断文件是否通过HTTP/POST方式上传
	$ext=getExt($filename);
	if(!in_array($ext,$allowExt)){
		$mes=iconv("utf-8","gb2312//IGNORE","非法文件类型");
		exit($mes);
	}
	if($size>$maxSize){
		$mes=iconv("utf-8","gb2312//IGNORE","文件上传不能超过".($maxSize/1024)."M");
		exit($mes);
	}
	$filename=getUniName().".".$ext;
	$destination="uploads/".$filename;
	if(is_uploaded_file($tmp_name)){
		if(move_uploaded_file($tmp_name, $destination)){
			$mes="上传文件成功";
		}else{
			$mes="移动文件失败";
		}
	}else{
		$mes="文件不是以POST方式上传";
	}
}else{
	switch ($error) {
		case 1:
			$mes="超过了配置文件上传文件的大小";//UPLOAD_ERR_INI_SIZE
			break;
		case 2:
			$mes="超过了表单设置上传文件的大小";//UPLOAD_ERR_FORM_SIZE
			break;
		case 3:
			$mes="文件部分被上传";//UPLOAD_ERR_PARTIAL
			break;
		case 4:
			$mes="未选中上传文件";//UPLOAD_ERR_NO_FILE
			break;
		case 6:
			$mes="未选中上传文件";//UPLOAD_ERR_NO_TMP_DIR
			break;
		case 7:
			$mes="文件不可写";//UPLOAD_ERR_CANT_WRITE
			break;
		case 8:
			$mes="程序意外终止";//UPLOAD_ERR_EXTENSION
			break;
		default:
			break;
	}
}
//服务器端
//1.>>file_uploads = On,支持通过HTTP POST方式上传文件
//2.>>upload_tmp_dir =临时文件保存目录
//3.>>upload_max_filesize = 2M 上传文件最大大小
//4.>>post_max_size = 8M 通过POST方式上传表单数据最大大小
//客户端配置
//<input type="hidden" name="MAX_FILE_SIZE" value="1024"/>
$mes=iconv("utf-8","gb2312//IGNORE",$mes);
echo $mes;
//生成唯一字符串
function getUniName(){
	return md5(uniqid(microtime(true),true));
}
//得到文件的扩展名
function getExt($filename){
	return strtolower(end(explode(".",$filename)));
}
?>