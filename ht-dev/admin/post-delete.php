<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/3/0003
 * Time: 9:32
 */
/*
 * 根据客户端传递的ID删除对应的数据
 *
 * */
require_once '../functions.php';
if(empty($_GET['id'])){
    exit('缺少必要参数');
}
//$id=(int)$_GET['id'];
$id=$_GET['id'];
//sql注入
//=> '1 or 1 = 1'
//is_numeric

$rows=xiu_execute('delete from posts where id in ('.$id.');');
//if($rows>0){};
//HTTP当中的referer用来表示页面当前请求的来源
header('Location: '.$_SERVER['HTTP_REFERER']);
