<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/17/0017
 * Time: 16:41
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

$rows=xiu_execute('delete from categories where id in ('.$id.');');
//if($rows>0){};
header('Location:/admin/categories.php');
