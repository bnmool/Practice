<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/15/0015
 * Time: 15:03
 */
//封装公共函数

require_once 'config.php';

session_start();
/*
 * 定义函数时一定要注意:函数名与内置函数冲突问题
 * js:typeof.fn==='function'
 * PHP判断函数是否定义的方式:function_exist('xiu_get_current_user');
 * */

/*
 *
 * 获取当前登录用户的信息，如果没有获取到则自动跳转到登录页
 *
 * */
function xiu_get_current_user(){
    if (empty($_SESSION['current_login_user'])) {
        //没有当前登录用户信息，意味着没有登录
        header('Location:/admin/login.php');
        exit();//没有必要在执行只有的代码
    }
    return $_SESSION['current_login_user'];
}

;
/*
 * 通过一个数据库查询获取数据
 * 获得多条数据
 * 返回的是索引数组
 * =>索引数组套关联数组
 * */
function xiu_fetch_all($sql){
    $conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASSWORD, XIU_DB_NAME);
    if (!$conn) {
        exit('连接失败');
    }
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        //查询失败
        return false;
    }
    $result=[];
    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = $row;
    }
    mysqli_free_result($query);
    mysqli_close($conn);
    return $result;
}

/*
 *
 * 获取单条数据
 * 返回的是关联数组
 * =>关联数组
 *
 * */
function xiu_fetch_one($sql){
    $res = xiu_fetch_all($sql);
    return isset($res[0]) ? $res[0] : null;
}
/*
 * 执行一个增删改语句
 * */
function xiu_execute($sql){
    $conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASSWORD, XIU_DB_NAME);
    if (!$conn) {
        exit('连接失败');
    }
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        //查询失败
        return false;
    }
    //对于增删改类的操作都是获取受影响行数
    $affected_rows=mysqli_affected_rows($conn);
    mysqli_close($conn);

    return $affected_rows;
}