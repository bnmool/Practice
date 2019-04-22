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


    mui('.mui-scroll-wrapper').scroll({
        deceleration: 0.0005, //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
        indicators: false,
    });

    mui.init({
        pullRefresh : {
            container:'.mui-scroll-wrapper',//下拉刷新容器标识，querySelector能定位的css选择器均可，比如：id、.class等
            down : {
                style:'circle',//必选，下拉刷新样式，目前支持原生5+ ‘circle’ 样式
                color:'#2BD009', //可选，默认“#2BD009” 下拉刷新控件颜色
                height:'50px',//可选,默认50px.下拉刷新控件的高度,
                range:'100px', //可选 默认100px,控件可下拉拖拽的范围
                offset:'0px', //可选 默认0px,下拉刷新控件的起始位置
                auto: true,//可选,默认false.首次加载自动上拉刷新一次
                callback: function(){
                    var that = this;
                    /*获取数据*/
                    window.pageNum = 1;
                    getProductList($.extend({},window.params),function(data){
                        /*渲染*/
                        $('#product_box').html(template('productTpl',{model:data}));
                        /*结束刷新状态*/
                        that.endPulldownToRefresh();
                        that.refresh(true);
                    });
                }
            },
            up : {
                height:50,//可选.默认50.触发上拉加载拖动距离
                auto:true,//可选,默认false.自动上拉加载一次
                contentrefresh : "正在加载...",//可选，正在加载状态时，上拉加载控件上显示的标题内容
                contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
                callback: function(){
                    var that = this;
                    /*获取数据*/
                    window.pageNum ++;
                    getProductList($.extend({},window.params),function(data){
                        if(!data.data.length || data.data.length < 10){
                            that.endPullupToRefresh(true);
                            return false;
                        }
                        /*渲染*/
                        $('#product_box').append(template('productTpl',{model:data}));
                        that.endPullupToRefresh();
                    });
                }
            }
        }
    });

});