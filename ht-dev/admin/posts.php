<?php

require_once '../functions.php';

xiu_get_current_user();


//处理分页参数
//============================================
$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
$size = 20;
// 必须 >= 1 && <= 总页数
// $page = $page < 1 ? 1 : $page;
if ($page < 1) {
    // 跳转到第一页
    header('Location: /admin/posts.php?page=1' . $search);
}
//计算出越过多少条
$offset = ($page - 1) * $size;
//上一页
$prevpage = $page - 1;
//下一页
$nextpage = $page + 1;

//接收筛选参数
$where = '1 = 1';
$search='';
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    $where .= ' and posts.category_id = ' . $_GET['category'];
    $search .= '&category='.$_GET['category'];
}
if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    $where .= " and posts.status = '{$_GET['status']}'";
    $search .= '&status='.$_GET['status'];
}
//$where .="1=1 and posts.category_id =1 and posts.status ='published'"
//$search =>"&category=1&status=published"
//==============================================

//获取全部数据
//============================================
$posts = xiu_fetch_all("
select
posts.id,
posts.title,
users.nickname as user_name,
categories.name as category_name,
posts.created,
posts.status
from posts 
inner join categories on posts.category_id=categories.id
inner join users on posts.user_id=users.id
where {$where}
order by posts.created desc
limit {$offset},{$size};
");


//查询全部的分类数据
$categories = xiu_fetch_all('select * from categories;');

//处理分页页码
//==========================================
//只要处理分页功能一定会用到最大的页码数
//$total_pages=ceil($total_count/$size);

//求出最大页码
$total_count = (int)xiu_fetch_one("select count(1) as count from posts 
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where};")['count'];
$total_pages = (int)ceil($total_count / $size);//51

//计算页码的开始
$visiables = 5;
$region = ($visiables - 1) / 2;//左右区间  2
$begin = $page - $region;//开始页码
$end = $begin + $visiables;//结束页码+1

//可能出现$begin和$end的不合理情况
//$begin必须>0
//确保$begin最小为1
if ($begin < 1) {
    //$begin修改意味着必须要改$end
    $begin = 1;
    $end = $begin + $visiables;
}
//$end必须<=最大页数
if ($end > $total_pages + 1) {
    //end超出范围
    $end = $total_pages + 1;
    //$end修改意味着必须要改$begin
    $begin = $end - $visiables;
    if ($begin < 1) {
        $begin = 1;
    }
}
//最大的页数 $total_pages=ceil($total_count/$size);

//========================================
//尾页执行
$numpages = ceil($total_pages);



//处理数据格式转换
//==========================================
/**
 * @param $status 英文状态
 * @return mixed|string  中文状态
 */
function convert_status($status)
{
    $dict = array(
        'published' => '已发布',
        'drafted' => '草稿',
        'trashed' => '回收站'
    );
    return isset($dict[$status]) ? $dict[$status] : '未知状态';
}

function convert_date($created)
{
    $timestamp = strtotime($created);
    return date('Y年m月d日 <b\r> H:i:s', $timestamp);
}

//function get_category($category_id){
//    return xiu_fetch_one("select name from categories where id ={$category_id}")['name'];
//}
//
//function get_user($user_id){
//    return xiu_fetch_one("select nickname from users where id ={$user_id}")['nickname'];
//}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Posts &laquo; Admin</title>
    <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="/static/assets/css/admin.css">
    <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
<script>NProgress.start()</script>

<div class="main">


    <div class="container-fluid">
        <div class="page-title">
            <h1>所有文章</h1>
            <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
        </div>
        <!-- 有错误信息时展示 -->
        <!-- <div class="alert alert-danger">
          <strong>错误！</strong>发生XXX错误
        </div> -->
        <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
            <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <select name="category" class="form-control input-sm">
                    <option value="all">所有分类</option>
                    <?php foreach ($categories as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo isset($_GET['category']) && $_GET['category'] == $item['id'] ? 'selected' : '' ?>
                        >
                            <?php echo $item['name']; ?>
                        </option>

                    <?php endforeach; ?>
                </select>

                <select name="status" class="form-control input-sm">
                    <option value="all">所有状态</option>

                    <option value="drafted"
                        <?php echo isset($_GET['status']) && $_GET['status'] == 'drafted' ? ' selected' : '' ;?>
                    >草稿
                    </option>

                    <option value="published"
                        <?php echo isset($_GET['status']) && $_GET['status'] == 'published' ? ' selected' : '' ;?>
                    >已发布
                    </option>

                    <option value="trashed"
                        <?php echo isset($_GET['status']) && $_GET['status'] == 'trashed' ? ' selected' : '' ;?>
                    >回收站
                    </option>
                </select>
                <button class="btn btn-default btn-sm">筛选</button>
            </form>
            <ul class="pagination pagination-sm pull-right">
                <li>
                    <?php  if($page > 1) : ?>
                    <a href="?page=1<?php echo "&category={$_GET['category']}"."&status={$_GET['status']}";?>">首页</a>
                    <a href="?page=<?php echo $prevpage."&category={$_GET['category']}"."&status={$_GET['status']}" ;?>">上一页</a>
                    <?php endif ?>
                </li>

                <?php for ($i = $begin; $i < $end; $i++) : ?>
                    <li <?php echo $i === $page ? 'class="active"' : ''; ?>>
                        <a href="?page=<?php echo $i ."&category={$_GET['category']}"."&status={$_GET['status']}"; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>


                <li>
                    <?php  if($page < $numpages) : ?>
                        <a href="?page=<?php echo $nextpage."&category={$_GET['category']}"."&status={$_GET['status']}";?>">下一页</a>
                        <a href="?page=<?php echo $numpages."&category={$_GET['category']}"."&status={$_GET['status']}";?>">尾页</a>
                    <?php endif ?>
                </li>
            </ul>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>标题</th>
                <th>作者</th>
                <th>分类</th>
                <th class="text-center">发表时间</th>
                <th class="text-center">状态</th>
                <th class="text-center" width="100">操作</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($posts as $item): ?>

                <tr>
                    <td class="text-center"><input type="checkbox"></td>
                    <td><?php echo $item['title']; ?></td>
                    <!--                    <td>--><?php //echo get_user($item['user_id']); ?><!--</td>-->
                    <!--                    <td>--><?php //echo get_category($item['category_id']); ?><!--</td>-->
                    <td><?php echo $item['user_name']; ?></td>
                    <td><?php echo $item['category_name']; ?></td>

                    <td class="text-center"><?php echo convert_date($item['created']); ?></td>
                    <!--一旦输出的判断或者转换逻辑过于复杂，不建议直接卸载混编位置-->
                    <td class="text-center"><?php echo convert_status($item['status']); ?></td>
                    <td class="text-center">
                        <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                        <a href="/admin/post-delete.php?id=<?php echo $item['id']?>" class="btn btn-danger btn-xs">删除</a>
                    </td>
                </tr>

            <?php endforeach ?>

            </tbody>
        </table>
    </div>
</div>

<?php $current_page = 'posts'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include 'inc/navbar.php'; ?>

<script src="/static/assets/vendors/jquery/jquery.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script>NProgress.done()</script>
</body>
</html>
