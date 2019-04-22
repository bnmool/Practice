<?php

require_once '../functions.php';

xiu_get_current_user();

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Navigation menus &laquo; Admin</title>
    <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="/static/assets/css/admin.css">
    <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
<script>NProgress.start()</script>

<div class="main">
    <?php include 'inc/navbar.php'?>
    <div class="container-fluid">
        <div class="page-title">
            <h1>导航菜单</h1>
        </div>

        <ul>
            <li></li>
        </ul>

        <!-- 有错误信息时展示 -->
        <!-- <div class="alert alert-danger">
          <strong>错误！</strong>发生XXX错误
        </div> -->
    </div>
</div>

<?php $current_page = 'douban'; ?>
<?php include 'inc/sidebar.php'; ?>

<script src="/static/assets/vendors/jquery/jquery.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>

<!--<script>-->
<!---->
<!--    //XHMHttpRequest不能发送对于不同源地址的请求-->
<!--// $.get('http://developers.douban.com/wiki/?title=movie_v2#in_theaters',{},function(res){-->
<!--//     console.log(res)-->
<!--// })-->
<!---->
<!--    function foo(res){-->
<!--        console.log(res);-->
<!--    }-->
<!---->
<!--</script>-->
<!--script可以对不同源地址请求-->
<!--<script src="http://developers.douban.com/wiki/?title=movie_v2#in_theaters?callback=foo"></script>-->


<script>

    $.ajax({
        url:'http://developers.douban.com/wiki/?title=movie_v2#in_theaters',
        dataType:'jsonp',
       success:function(res){
           $(res.subjects).each(function(i,item){
               $('#movies').append(`<li><img src="${item.images.large}">${item.title}</li>`);
           })
       }
    });

</script>


<script>NProgress.done()</script>
</body>
</html>
