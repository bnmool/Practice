<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/8/0008
 * Time: 15:13
 */
//var_dump($_Files['avatar']);


//接收文件
//保存文件
//返回这个文件的访问URL
if(empty($_FILES['avatar'])){
    exit('必须上头像');
}

$avatar=$_FILES['avatar'];
if($avatar['error']!==UPLOAD_ERR_OK){
    exit('上传失败');
}
//校验类型大小

//移动文件到网站文件之内
$ext=pathinfo($avatar['name'],PATHINFO_EXTENSION);

$target='../../static/uploads/img-'.uniqid().'.'.$ext;

if(!move_uploaded_file($avatar['tmp_name'],$target)){
    exit('上传失败');
};
//上传成功
echo substr($target,5);