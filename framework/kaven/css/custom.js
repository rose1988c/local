$(function(){function h(){function a(a){ueI=(a+e)/f;return parseInt(ueI)}function b(b){b=a(b);0<b&&(b=300*b-20,$(".common").width(b),a(b))}function g(a){switch(a){case 6:$(".index_push_designer,.index_push_jobs").width(600),$(".index_push_experience").width(580)}}var c=$(window).width(),d=document.getElementById("index_list_round")?$(".listbox").length+1:$(".listbox").length,f=300,e=20;$.fn.hMR=function(){$(this).css({marginRight:e})};$.fn.nMR=function(){$(this).css({marginRight:"0px"})};1780<=c? ($(".common").width(1780),a(1780)):b(c);l(0);$(".scw").width(580);$(".sc0,.sc1,.sc2,.sc3,.scs,.search,#uebaike,.ueactive,.upmenu,.ueactive,#pages ul li,#pages ul .prev,#pages ul .next,#showcase,#case_info,#index_notice").show();$(".sc0,.sc1,.sc2,.sc3,.scw,.uetab").hMR();$("#showcase").height(400);$(".ctround").width(1180);$(".ctround img").width(1180);$("#ctround").css("marginLeft",-610);$("#time_line img").width(136);$("#time_line").css("top",400);$("#time_line ul,#case_info").width(1180).css("marginLeft", -590);$("#sort").show();$(".upload_wrap").width($(".common").width());280>$(window).width()&&$(".common").width(280);document.getElementById("showcase")&&$("body").css("paddingTop",100);switch(ueI){case 1:$(".sc3,.sc2,.sc1,.scs,.upmenu,#search,#searcht,#uebaike,.ueactive,#pages ul li,#showcase,#case_info,#index_notice").hide();$("#pages ul .prev,#pages ul .next").show();$(".scw,.uetab").nMR();$(".listbox").hMR();$(".login").addClass("logintoggle");$("#header").addClass("ogrid");l(1);$(".scw").width(280); g(ueI);document.getElementById("switchresult")||$("#sort").hide();document.getElementById("switchresult")&&$("#sort").hide();document.getElementById("showcase")&&$("body").css("paddingTop",50);break;case 2:$(".sc3,.sc2,.sc1,.scs,#search,.login ul,.ueactive,#showcase,#case_info,#index_notice").hide();$(".scw").nMR();$(".listbox").hMR();$(".login").addClass("logintoggle");$("#header").addClass("ogrid");$(".nav ul").show();g(ueI);l(1);document.getElementById("switchresult")||$("#sort").hide();document.getElementById("switchresult")&& ($("#sort").show(),$("#searcht").css("marginTop",8));document.getElementById("showcase")&&$("body").css("paddingTop",50);break;case 3:$(".sc3,.sc2,.sc1,#index_notice,.login ul,.upmenu").hide();$(".nav ul").show();$(".sc0").nMR();$(".listbox").hMR();$(".login").removeClass("logintoggle");$("#header").removeClass("ogrid");$(".ctround").width(880);$(".ctround img").width(880);$("#ctround").css("marginLeft",-460);$("#showcase").height(298);$("#time_line").css("top",300);$("#time_line ul,#case_info").width(880).css("marginLeft", -440);$(".login").addClass("logintoggle");document.getElementById("switchresult")&&$("#sort").hide();g(ueI);break;case 4:$(".sc3,.sc2").hide();$(".upmenu,.login ul,.nav ul").show();$(".sc1").nMR();$(".listbox").hMR();$(".login").removeClass("logintoggle");$("#header").removeClass("ogrid");g(ueI);break;case 5:$(".sc3").hide();$(".upmenu,.login ul,.nav ul").show();$(".sc2").nMR();$(".listbox").hMR();$(".login").removeClass("logintoggle");$("#header").removeClass("ogrid");g(ueI);$("#sort").show();break; case 6:$(".upmenu,.login ul,.nav ul").show(),$(".sc3").nMR(),$(".listbox").hMR(),$(".login").removeClass("logintoggle"),$("#header").removeClass("ogrid"),g(ueI)}(function(){for(i=1;i<=d/ueI;i++){var a=document.getElementById("index_list_round")?i*ueI-2:i*ueI-1;$(".listbox").eq(a).css({marginRight:"0"})}})()}function l(a){1==a&&$(".ogrid .nav ul").css({left:-440,width:"auto"});0==a&&($("#maskblack").remove(),$("body").unbind("touchmove"))}function n(){$(".nav ul").animate({left:-440},300,"easeOutCubic"); $("#maskblack").fadeOut(300,function(){$("#maskblack").remove();$("body").unbind("touchmove")})}function p(){d&&(d=!1,$(".activect").animate({top:-50},900,"easeInOutBack",function(){$(".activect li:first").clone().appendTo(".activect");$(".activect li:first").remove();$(".activect").css("top",0);d=!0}))}function q(a){e&&(e=!1,1!=a?(c==m-1?c=0:c++,$(".index_list_content li:last").clone().prependTo(".index_list_content"),$(".index_list_info").animate({bottom:-70},200,"easeOutQuart",function(){$(".index_list_info .pd10").hide(); $(".index_list_info .pd10").eq(c).show();$(".index_list_content li:last").animate({width:0},1E3,"easeOutQuart",function(){$(".index_list_content li:last").remove();c&&$(".index_list_info").animate({bottom:0},300,"easeOutQuart");e=!0})})):(0==c?c=m-1:c--,$(".index_list_content li:first").css("width",0).clone().appendTo(".index_list_content"),$(".index_list_content li:first").remove(),$(".index_list_info").animate({bottom:-70},200,"easeOutQuart",function(){$(".index_list_info .pd10").hide();$(".index_list_info .pd10").eq(c).show(); $(".index_list_content li:last").animate({width:580},1E3,"easeOutQuart",function(){c&&$(".index_list_info").animate({bottom:0},300,"easeOutQuart");e=!0})})))}function r(){f&&(f=!1,$(".index_notice_li").animate({top:-40},900,"easeInOutBack",function(){$(".index_notice_li li:first").clone().appendTo(".index_notice_li");$(".index_notice_li li:first").remove();$(".index_notice_li").css("top",0);f=!0}))}function u(){$(".favorites").append('<div class="add_favorites"></div>');dfgdfsg=setTimeout(function(){$(".add_favorites").addClass("add_favorites_hover")}, 600);dsfdssdfgf=setTimeout(function(){$(".add_favorites").css("opacity",0);clearTimeout(dfgdfsg);clearTimeout(dsfdssdfgf)},1E3);dsfdssdfsgf=setTimeout(function(){$(".add_favorites").remove();clearTimeout(dsfdssdfsgf)},1400)}/mobile/.test(navigator.userAgent.toLowerCase())?$(".logo img").attr("src","assets/images/logo2.png"):$(".logo img").attr("src","assets/images/logo.png");$.fn.forRetina=function(a,b,c){1==a?$(this).css({backgroundImage:"url("+b+")",backgroundSize:""+c+""}):$(this).attr("style", "")};/mobile/.test(navigator.userAgent.toLowerCase())?($(".ogrid .navlist a,.logintoggle .loginmenu a,.addfavorite i,.fold,.boxtags ul,.cate li a,.searchtags .closetag,.searchbtn,.cate3 .selected .gselected, .cate4 .selected .gselected,.postdate,.pages li a,.ccolor h3 i,.ctags h3 i,.closealert,.upmenu .upload,.upmenu .favorites,#pages ul .prev a,#pages ul .next a,.likeaswitcher .tagselected .gselected,.male_icon,.artprev,.artnext,#backtop,#veiwall span").forRetina(1,"assets/images/iconsx2.png","240px 500px"), $(".cate li.home a").forRetina(0)):$(".ogrid .navlist a,.logintoggle .loginmenu a,.addfavorite i,.fold,.boxtags ul,.cate li a,.searchtags .closetag,.searchbtn,.cate3 .selected .gselected, .cate4 .selected .gselected,.postdate,.pages li a,.ccolor h3 i,.ctags h3 i,.closealert,.upmenu .upload,.upmenu .favorites,#pages ul .prev a,#pages ul .next a,.likeaswitcher .tagselected .gselected,.male_icon,.artprev,.artnext,#backtop,#veiwall span").forRetina(0);h();$(window).resize(h);document.getElementById("switchresult")&& 880<=$(".common").width()&&($(".selected").each(function(){var a=$(this).find(".acate").html(),a=$('<li><a href="javacript:;">'+a+'</a><a class="closetag" href="javascript:return false;"></a></li>');$(".searchtags ul").prepend(a);a.data("target",$(this))}),0<$(".searchtags li").length?$("#search").show():$("#search").hide());$(".loginmenu").bind("click",function(){"none"==$(this).parent().find("ul").css("display")?$(this).parent().find("ul").slideDown(200,"easeOutCubic"):$(this).parent().find("ul").slideUp(200, "easeOutCubic")});$(".ogrid .navlist").bind("touchstart",function(){if(-440==parseInt($(".nav ul").css("left"),10)){var a=($(window).width()-$(".common").width())/2+100;$(".nav ul").css({width:a});$(".nav ul").animate({left:0},300,"easeOutCubic");$("body").append('<div id="maskblack"></div>');$("#maskblack").fadeIn(300);$("body").bind("touchmove",function(a){a.preventDefault();a.stopImmediatePropagation()});$("#maskblack").bind("click",function(){n()})}else n()});$(".cate li").click(function(){var a= $(this).index();"none"==$(".cates").eq(a).css("display")&&($(".cate li").removeClass("sccur"),$(this).addClass("sccur"),$(".cate li").find("a").removeClass("sccurt"),$(".sccur").find("a").addClass("sccurt"),$(".cates").each(function(){$(this).index()!=a?$(this).hide():$(this).stop(!0,!1).slideDown(200,"jswing")}))});$(".cate li:last").css({marginRight:0});$(".sccur").find("a").addClass("sccurt");$(".cate4 li").each(function(){var a=100*-$(this).index();$(this).find("a").css({backgroundPosition:""+ a+"px 5px"})});3>=$(".searchtags li").length&&$(".cates li").click(function(){var a=$(".searchtags ul").html(),b=$(this).find(".acate").html(),c=$(this).find(".sca").html();0>=a.indexOf(b)||$(".searchtags li:contains("+c+")").remove();$(this).toggleClass("selected");a=[];for(b=0;b<$(".cates li.selected").length;b++)a.push($(".cates li.selected").eq(b).attr("data-href"));window.location.href=$("#select").attr("data-href")+a.join("&")});$(".closetag").click(function(){$(this).parent().data("target").removeClass("selected"); for(var a=[],b=0;b<$(".cates li.selected").length;b++)a.push($(".cates li.selected").eq(b).attr("data-href"));window.location.href=$("#select").attr("data-href")+a.join("&")});$(".cate ul li").mouseover(function(){var a=$(this).index(),b=40*a+21,c=80*(a-1);0!=a&&(sfaddsf=setTimeout(function(){$("#catemove").fadeIn(200);$(".catedecoration").animate({left:b},200,"easeOutQuart");$(".catedecorationtext").animate({left:b},200,"easeOutQuart");$(".catedecorationtext .tul").animate({marginLeft:-c},500,"easeOutQuart")}, 250))}).mouseleave(function(){clearTimeout(sfaddsf)});$(".cate").mouseleave(function(){$("#catemove").fadeOut(200)});$.fn.showSlide=function(){$(this).hover(function(){var a=$(this);dsfdjsk=setTimeout(function(){a.find(".sc_detail").stop(!0,!1).animate({bottom:"0"},450,"easeOutQuart")},50)},function(){clearTimeout(dsfdjsk);$(this).find(".sc_detail").stop(!0,!1).animate({bottom:"-60px"},380,"easeOutQuart")})};$(".newlist").showSlide();$(".colorsmall li").hover(function(){$(".colorsmall li a").css({"z-index":0}); 0>$(this).attr("class").indexOf("white")?($(this).css({"z-index":2}),$(this).find("a").css({"z-index":3}).stop(!0,!1).animate({width:"31px",height:"12px",left:"-3px",top:"-3px"},80,"easeOutQuart")):$(this).find("a").css({"z-index":3}).stop(!0,!1).animate({width:"29px",height:"10px",left:"-3px",top:"-3px"},80,"easeOutQuart")},function(){$(".colorsmall li").css({"z-index":0});$(".colorsmall li a").css({"z-index":0});0>$(this).attr("class").indexOf("white")?$(this).find("a").stop(!0,!1).animate({width:"25px", height:"6px",left:"0",top:"0"},150,"easeOutQuart"):$(this).find("a").stop(!0,!1).animate({width:"23px",height:"4px",left:"0",top:"0"},150,"easeOutQuart")});$(".boxtags").each(function(){$(this).find("li").eq(0).css({paddingLeft:"0",background:"none"})});$(".listtags").each(function(){$(this).find("li").eq(0).css({paddingLeft:"0",background:"none"})});var k=!0;$(".loginmask").css("opacity",0.8);$(".thirdlogin ul li:odd").css({marginRight:0});$(".openlogin").click(function(){k&&"0px"!=$("#loginalert").css("top")&& ($("#loginalert").show(),$(".loginmask").fadeIn(500),$("#loginalert").animate({top:0},400,"easeOutQuart"))});$(".loginmask,.closealert").click(function(){k&&(k=!1,$("#loginalert").animate({top:-600},400,"easeOutQuart",function(){$("#loginalert").hide();k=!0}),$(".loginmask").fadeOut(500))});$(".userpanel dl").hover(function(){$(this).parent().find("dd").show();$(this).find("dt").addClass("userpanel_cur")},function(){$(this).find("dt").removeClass("userpanel_cur");$(this).parent().find("dd").hide()}); $(".userpanel dl li").each(function(){$(this).find("a").css("backgroundPosition","15px "+-30*$(this).index()+"px")});$.fn.selectdpost=function(){$(this).click(function(){0>$(this).attr("class").indexOf("selected")?$(this).addClass("tagselected"):$(this).removeClass("tagselected")})};$.fn.selectdpostFbd=function(){var a=0;$(this).click(function(){0>$(this).attr("class").indexOf("selected")?3>$(this).parent().find(".tagselected").length&&($(this).addClass("tagselected"),$(this).attr("name",a++)):$(this).removeClass("tagselected")})}; $(".psttag li").selectdpost();$(".likeaswitcher li").selectdpostFbd();$(".pstcate li").selectdpostFbd();$(".postlayout li").each(function(){var a=100*-$(this).index();$(this).find("a").css({backgroundPosition:""+a+"px 5px"})});$(".newlist .likeit,.morewebsite .likeit,.nsmall .likeit,#showcase .likeit").hover(function(){var a=$(this);dsfdsfdf=0>$(this).attr("class").indexOf("dontlike")?setTimeout(function(){$(".likeiti").remove();a.prepend('<div class="likeiti">\u559c\u6b22</div>');$(".likeiti").fadeIn(500)}, 100):setTimeout(function(){$(".likeiti").remove();a.prepend('<div class="likeiti">\u4e0d\u559c\u6b22</div>');$(".likeiti").fadeIn(500)},100)},function(){clearTimeout(dsfdsfdf);$(".likeiti").fadeOut(500,function(){$(this).parent().find(".likeiti").remove()})});$(".favoriteslist .dontlikeit").click(function(){$(this).parent().parent().parent().parent().parent().remove();h()});var s=$(".fsactive").index();$("#fswitch li").mouseover(function(){var a=140*$(this).index();$(".fswitcharrow").stop(!0,!1).animate({left:a}, 260,"easeOutQuart")});$("#fswitch ul").mouseleave(function(){var a=0*s;$(".fswitcharrow").stop(!0,!1).animate({left:a},260,"easeOutQuart")});$(".fswitcharrow").css({},260,"easeOutQuart");$(".artprev,.artnext").css("opacity",0.3);$(".artprev,.artnext").hover(function(){$(this).css("opacity",1)},function(){$(this).css("opacity",0.3)});$(window).scroll(function(){0<$(window).scrollTop()?($("#backtop").css({bottom:10}),$("#backtop").click(function(){if(!$("html,body").is(":animated"))return $("html,body").animate({scrollTop:0}, 500),!1})):$("#backtop").css({bottom:-40})});$(".nsmall:last").css({marginBottom:0});$(".activeprev").click(function(){d&&(d=!1,$(".activect li:last").clone().prependTo(".activect"),$(".activect").css("top",-50),$(".activect").animate({top:0},900,"easeInOutBack",function(){$(".activect li:last").remove();d=!0}))});$(".activenext").click(p);var d=!0;$(".ueactive").hover(function(){clearInterval(falkdaf)},function(){falkdaf=setInterval(p,5E3)}).trigger("mouseleave");$(".response").append('<div class="layout-r"><img src="assets/images/layout-r.png"></div>'); jQuery.fn.loading=function(a){$(this).css("background",'url("assets/images/'+a+'") no-repeat 0px -1px')};var t=!1;$(".fsactive").length&&"\u559c\u6b22"==$(".fsactive a b").text()&&(t=!0);$(".newlist .likeit,.morewebsite .likeit,.nsmall .likeit").click(function(){if($(".openlogin").length)return $(".openlogin a").click(),!1;var a=$(this).attr("data-postid"),b=$(this),c=parseInt($(this).find("a").html(),10);0>b.attr("class").indexOf("dontlikeit")?(b.addClass("loading"),0<b.attr("class").indexOf("loading")&& b.loading("loading.gif"),YYService.call("addLike",{complete:function(){b.attr("style","");b.addClass("dontlikeit");b.removeClass("loading");b.find("a").html(c+1);u()},error:function(){alert("\u60a8\u6162\u70b9\u70b9")}},{postid:a})):YYService.call("removeLike",{complete:function(){b.removeClass("dontlikeit");b.find("a").html(c-1);t&&(b.parent().parent().parent().parent().parent().remove(),h())},error:function(){alert("\u60a8\u6162\u70b9\u70b9")}},{postid:a})});$("#addlike").click(function(){if($(".openlogin").length)return $(".openlogin a").click(), !1;var a=$(this);$(this).hasClass("active")?YYService.call("removeLike",{complete:function(){a.removeClass("active");$("span",a).html(parseInt($("span",a).html())-1);$("#user_active").remove()},error:function(){alert("\u60a8\u6162\u70b9\u70b9")}},{postid:$(this).attr("data-postid")}):YYService.call("addLike",{complete:function(){a.addClass("active");$("span",a).html(parseInt($("span",a).html())+1);$("#likeuser").prepend($(".posteravatar a").clone());$("#likeuser a:first").attr("id","user_active")}, error:function(){alert("\u60a8\u6162\u70b9\u70b9")}},{postid:$(this).attr("data-postid")});return!1});$(".addfavorite").click(function(){if($(".openlogin").length)return $(".openlogin a").click(),!1;var a=$(this).attr("data-postid"),b=$(this),c=$(this).find(".like_i"),d=parseInt($(this).find(".like_i").html());b.hasClass("addfavorite_l")?YYService.call("removeLike",{complete:function(){b.removeClass("addfavorite_l");c.html(d-1);$("#user_active").remove()},error:function(){alert("\u60a8\u6162\u70b9\u70b9")}}, {postid:a}):YYService.call("addLike",{complete:function(){b.addClass("addfavorite_l");c.html(d+1);$("#likeuser").append($(".posteravatar a").clone())},error:function(){alert("\u60a8\u6162\u70b9\u70b9")}},{postid:a})});$(".baikepush").hover(function(){var a=$(this);dsfsddsf=setTimeout(function(){a.find(".baikehover").fadeIn(200)},200)},function(){clearTimeout(dsfsddsf);$(this).find(".baikehover").fadeOut(500)});$(".othercategories_cur").append('<div class="othercategories_cur_b"></div>');$(".index_list_info .pd10:first").show(); var e=!0,m=$(".index_list_content li").length,c=0;$(".index_list_arrow_next").click(function(){q()});$(".index_list_arrow_prev").click(function(){q(1)});$("#index_list_round").hover(function(){clearInterval(indexListRound);if(!c)return $(".index_list_arrow_prev").addClass("index_list_cur"),$(".index_list_arrow_next").addClass("index_list_cur"),$(".index_list_info").stop().css("bottom",-70),!1;$(".index_list_info").stop(!0,!0).animate({bottom:0},300,"easeOutQuart",function(){$(".index_list_arrow_prev").addClass("index_list_cur"); $(".index_list_arrow_next").addClass("index_list_cur")})},function(){$(".index_list_info").stop(!0,!0).animate({bottom:-70},300,"easeOutQuart",function(){$(".index_list_arrow_prev").removeClass("index_list_cur");$(".index_list_arrow_next").removeClass("index_list_cur")});indexListRound=setInterval(function(){c==m-1?c=0:c++;$(".index_list_content li:last").clone().prependTo(".index_list_content");$(".index_list_info .pd10").hide();$(".index_list_info .pd10").eq(c).show();$(".index_list_content li:last").animate({width:0}, 1E3,"easeOutQuart",function(){$(".index_list_content li:last").remove();e=!0})},8E3)}).trigger("mouseleave");$(".index_notice_prev").click(function(){f&&(f=!1,$(".index_notice_li li:last").clone().prependTo(".index_notice_li"),$(".index_notice_li").css("top",-40),$(".index_notice_li").animate({top:0},900,"easeInOutBack",function(){$(".index_notice_li li:last").remove();f=!0}))});$(".index_notice_next").click(r);var f=!0;$("#index_notice").hover(function(){clearInterval(fdsafdasdasf)},function(){fdsafdasdasf= setInterval(r,5E3)}).trigger("mouseleave");$.fn.placeholder=function(){var a=$(this),b=a.attr("value");a.focus(function(){a.attr("value")==b&&a.attr("value","")}).blur(function(){""==a.attr("value")&&a.attr("value",b)})};$(".loginuserpasswordt").focus(function(){"\u5bc6\u7801"==$(this).attr("value")&&($(".loginuserpasswordt").hide(),$(".loginuserpasswordp").show(),document.getElementsByName("password")[0].focus())});$(".loginuserpasswordp").blur(function(){""==$(this).attr("value")&&($(".loginuserpasswordt").show(), $(".loginuserpasswordp").hide(),$(".loginuserpasswordt").attr("value","\u5bc6\u7801"))});$(".loginusername").placeholder();$(".searchtxt").placeholder();if(document.getElementById("search_class_emu")&&!/mobile/.test(navigator.userAgent.toLowerCase())){$("#search_class option:eq("+$(".search_class_cur").index()+")").attr("selected",!0);$(".searchtxt").focus(function(){$("#search_class_emu").stop(!0,!0).slideDown(500,"easeOutQuart")});$("#header").mouseleave(function(){$("#search_class_emu").stop(!0, !0).slideUp(500,"easeOutQuart");document.getElementById("searcht").getElementsByTagName("input")[0].blur()});var v=$("#searcht form").attr("data-action");$("#search_class_emu li").click(function(){$("#search_class_emu li").removeClass("search_class_cur");$(this).addClass("search_class_cur");$("#search_class option[selected]").removeAttr("selected");var a=$(this).attr("data-cat")||"";$("#searcht form").attr("action",v+a+"/")})}$(".setp_pofile_input_user").placeholder();$(".setp_pofile_input_email").placeholder(); $(".setp_pofile_input_v_password").focus(function(){"\u5bc6\u7801"==$(this).attr("value")&&($(".setp_pofile_input_v_password").hide(),$(".setp_pofile_input_password").show(),document.getElementsByName("password")[0].focus())});$(".setp_pofile_input_password").blur(function(){""==$(this).attr("value")&&($(".setp_pofile_input_v_password").show(),$(".setp_pofile_input_password").hide(),$(".setp_pofile_input_v_password").attr("value","\u5bc6\u7801"))});$(".setp_pofile_checkb").click(function(){0<=$(this).attr("class").indexOf("setp_pofile_check_true")? ($(this).removeClass("setp_pofile_check_true"),document.getElementById("setp_pofile_check").checked=!1):($(this).addClass("setp_pofile_check_true"),document.getElementById("setp_pofile_check").checked=!0)});$("#sigup_now,.reg a").click(function(){$("#reg_setp,#setp_quicklogin").show();$("#reg_setp").animate({left:0},500,"easeOutQuart")});$(".back_setp").click(function(){"block"==$("#setp_quicklogin").css("display")&&$("#reg_setp").animate({left:"100%"},500,"easeOutQuart",function(){$("#reg_setp,#setp_quicklogin").hide()})}); $(".home").click(function(){"none"==$(this).find("dl").css("display")&&$(this).find("dl").stop(!0,!0).slideDown(500,"easeOutCubic")});$(".home").mouseover(function(){$(this).hasClass("sccur")&&($(this).find("dl").stop(!0,!0).slideDown(500,"easeOutCubic"),$(this).find("i").addClass("rotatei"))});$("#select").mouseleave(function(){$(".home").find("dl").stop(!0,!0).slideUp(500,"easeOutCubic");$(".home").find("i").removeClass("rotatei")});$(".sccur.home dd").click(function(){$(".sccur.home dd").removeClass("cate_home_cur"); $(this).addClass("cate_home_cur");$(".home a.sccurt span").html($(".cate_home_cur").html());$(".home").find("dl").stop(!0,!0).slideUp(500,"easeOutCubic");$(".home").find("i").removeClass("rotatei")});$("#sort dl").hover(function(){var a=$("#sort dd").length,b=$("#sort dd").height();$("#sort dl").stop(!0,!0).animate({height:(a+1)*b},500,"easeOutCubic");$("#sort dt i").addClass("rotatei")},function(){$("#sort dl").stop(!0,!0).animate({height:$("#sort dd").height()},500,"easeOutCubic");$("#sort dt i").removeClass("rotatei")}); $(".upload").hover(function(){$(".upload .upload_a").show()},function(){$(".upload .upload_a").hide()});$("#postbtn.sitemessage").click(function(a){a={};a.title=$.trim($("#yourname").val());if(!a.title)return alert("\u540d\u5b57\u4e0d\u80fd\u4e3a\u7a7a"),!1;a.email=$.trim($("#youremail").val());if(!a.email)return alert("\u90ae\u7bb1\u4e0d\u80fd\u4e3a\u7a7a"),!1;if(!/^[\w\.\-\+]+@([\w\-]+\.)+[a-z]{2,4}$/i.test(a.email))return alert("\u90ae\u7bb1\u683c\u5f0f\u4e0d\u6b63\u786e"),!1;a.content=$.trim($("#yourcontent").val()); if(!a.content)return alert("\u5185\u5bb9\u4e0d\u80fd\u4e3a\u7a7a"),!1;YYService.call("siteMessage",{complete:function(){$("#yourname,#youremail,#yourcontent").val("");alert("Thank You!");window.location.reload()},error:function(){alert("\u63d0\u4ea4\u5931\u8d25!")}},a);return!1});$(".user_set a").click(function(a){if($(".openlogin").length)return $(".openlogin a").click(),!1})});