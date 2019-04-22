<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/6/0006
 * Time: 16:45
 */
require_once '../../functions.php';
if(empty($_GET['id'])){
    exit('缺少必要参数');
}
//$id=(int)$_GET['id'];
$id=$_GET['id'];
//sql注入
//=> '1 or 1 = 1'
//is_numeric

$rows=xiu_execute('delete from comments where id in ('.$id.');');
//if($rows>0){};
header('Content-Type:application/json');
echo json_encode($rows>0);