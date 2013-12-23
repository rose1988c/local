jQuery(document).ready(function(){
    $(".comment-body").live("mouseenter mouseleave",
    function(event) {
        if (event.type == "mouseenter") {
            $(this).find(".reply").stop(false, true).fadeIn();
        } else {
            $(this).find(".reply").stop(false, true).fadeOut();
        };
    });
    $(function() {
        var o = 0;
        var timeInterval = 5000;
        var $cont = $(".tab-content ul");
        var $title = $(".tab-title span");
        $cont.hide();
        $($cont[0]).show();
        function auto() {
            o < $cont.length - 1 ? o++:o = 0;
            $cont.eq(o).fadeIn(800).siblings().hide();
            $title.eq(o).addClass("selected").siblings().removeClass("selected");
        }
        set = window.setInterval(auto, timeInterval);
    });
	
//弹性搜索框
$(".field").focus(function(){
$(this).stop(true,false).animate({width:"250px"},"fast");
})
.blur(function(){
$(this).animate({width:"200px"},"fast");
});
$(function () {   
         var scrtime;   
         $("#rcslider").hover(function(){   
                 clearInterval(scrtime);   
         },function(){   
         scrtime = setInterval(function(){   
                 var $ul = $("#rcslider");
                 var liHeight = $ul.find("li:last").height();   
                 $ul.animate({marginTop : liHeight+1+"px"},1000,function(){
                 $ul.find("li:last").prependTo($ul);   
                 $ul.find("li:first").hide();   
                 $ul.css({marginTop:0});   
                 $ul.find("li:first").fadeIn(6000);
                 });          
         },4000);
         }).trigger("mouseleave");     
 });
$(function($){
$body=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');//修复Opera滑动异常地，加过就不需要重复加了。
$('#shang').mouseover(function(){//鼠标移到id=shang元素上触发事件
        up();
    }).mouseout(function(){//鼠标移出事件
        clearTimeout(fq);
    }).click(function(){//点击事件
        $body.animate({scrollTop:0},400);//400毫秒滑动到顶部
});
$('#xia').mouseover(function(){
        dn();
    }).mouseout(function(){
        clearTimeout(fq);
    }).click(function(){
        $body.animate({scrollTop:$(document).height()},400);//直接取得页面高度，不再是手动指定页尾ID
});
$('#comt').click(function(){
    $body.animate({scrollTop:$('#respond').offset().top},800);//滑动到id=comments元素，遇到不规范的主题需调整
});
}); 
//文章标题链接点击滑动
$('.excerpt h2 a').hover(function(){
jQuery(this).stop().animate({marginLeft:"5px"},300);
},function(){
jQuery(this).stop().animate({marginLeft:"0px"},300);
});
//侧边栏链接点击滑动
$('#sidebar li a').hover(function(){
$(this).stop().animate({'left':'4px'},'600');
},function(){
$(this).stop().animate({'left':'0px'},'600');
});
//侧边栏TAB效果
$('.tab-title span').click(function(){
	jQuery(this).addClass("selected").siblings().removeClass();
	jQuery(".tab-content > ul").eq(jQuery('.tab-title span').index(this)).fadeIn(800).siblings().hide();
});
//图片渐隐
jQuery('img').hover(
function() {jQuery(this).fadeTo("fast", 0.5);},
function() {jQuery(this).fadeTo("fast", 1);
});
//新窗口打开
$("a[rel='external'],a[rel='external nofollow']").click(
function(){window.open(this.href);return false})
//顶部微博等图标渐隐
$('.icon1,.icon2,.icon3,.icon4,').wrapInner('<span class="hover"></span>').css('textIndent','0').each(function () {
jQuery('span.hover').css('opacity', 0).hover(function () {
jQuery(this).stop().fadeTo(350, 1);
},function () {
jQuery(this).stop().fadeTo(350, 0);
});
});
//预加载广告
function speed_ads(loader, ad) {
var ad = document.getElementById(ad),
loader = document.getElementById(loader);
if (ad && loader) {
ad.appendChild(loader);
loader.style.display='block';
ad.style.display='block';
}
}
window.onload=function() {
speed_ads('adsense-loader1', 'adsense1');
speed_ads('adsense-loader2', 'adsense2');
speed_ads('adsense-loader3', 'adsense3');
};
// 文字滚动
(function($){
$.fn.extend({
Scroll:function(opt,callback){
if(!opt) var opt={};
var _this=this.eq(0).find("ul:first");
var        lineH=_this.find("li:first").height(),
line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10),
speed=opt.speed?parseInt(opt.speed,10):7000, //卷动速度，数值越大，速度越慢（毫秒）
timer=opt.timer?parseInt(opt.timer,10):7000; //滚动的时间间隔（毫秒）
if(line==0) line=1;
var upHeight=0-line*lineH;
scrollUp=function(){
_this.animate({
marginTop:upHeight
},speed,function(){
for(i=1;i<=line;i++){
_this.find("li:first").appendTo(_this);
}
_this.css({marginTop:0});
});
}
_this.hover(function(){
clearInterval(timerID);
},function(){
timerID=setInterval("scrollUp()",timer);
}).mouseout();
}
})
})(jQuery);
$(document).ready(function(){
$("#bulletin").Scroll({line:1,speed:1000,timer:5000});//修改此数字调整滚动时间
});
});
function up(){
   $wd = $(window);
   $wd.scrollTop($wd.scrollTop() - 1);
   fq = setTimeout("up()", 50);
}
function dn(){
   $wd = $(window);
   $wd.scrollTop($wd.scrollTop() + 1);
   fq = setTimeout("dn()", 50);
}
