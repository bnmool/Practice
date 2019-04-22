$(function(){
    $('.t_search a').on('tap',function(){
        //跳转去搜索列表页 并且需要带上关键字
        var key=$.trim($('input').val());
        //判断 没有关键字就提示用户"请输入关键字搜索"
        if(!key){
            //mui 消息提示
            mui.toast('请输入关键字再搜索')
        }else{
            //如果合法
            //searchList.html?key=xxxx
            location.href='./searchList.html?key='+key;
        }
    })
});