$(function(){
    $(".cate_left ul li").on("click",function(){
        $(this).addClass("now").siblings().removeClass("now");
        var idx=$(this).index();
        $(".cate_right ul").eq(idx).addClass("selected").siblings().removeClass("selected")
    });
});