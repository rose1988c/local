$(document).ready(function() { 
//字体大小控制
$('#resizer li').click(function(){
var fontSize = 13;
var name = $(this).attr('id');
if (name == 'f_s') {
fontSize -= 2
} else if (name == 'f_l') {
fontSize += 2
} else if (name == 'f_m') {
fontSize == 13
}
$('.context').css('font-size', fontSize + 'px')
});
//社交分享
Ashare();
function Ashare() {
	var thelink = encodeURIComponent(document.location), thetitle = encodeURIComponent(document.title.substring(0, 60)), windowName = '分享到', param = getParamsOfShareWindow(600, 560),
	A_qzone = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + thelink + '&title=', 
	A_tqq = 'http://v.t.qq.com/share/share.php?title=' + thetitle + '&url=' + thelink + '&site=', 
	A_sina = 'http://v.t.sina.com.cn/share/share.php?url=' + thelink + '&title=' + thetitle, 
	A_wangyi = 'http://t.163.com/article/user/checkLogin.do?info=' + thetitle + thelink, 
	A_renren = 'http://share.renren.com/share/buttonshare?link=' + thelink + '&title=' + thetitle, 
	A_kaixin = 'http://www.kaixin001.com/repaste/share.php?rtitle=' + thetitle + '&rurl=' + thelink, 
	A_xiaoyou = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url=' + thelink + '&title=' + thetitle, 
	A_baidu = 'http://cang.baidu.com/do/add?it=' + thetitle + '&iu=' + thelink;
	$('.Ashare').each(
		function() {
			$(this).attr('title', windowName + $(this).text());
			$(this).click(
				function() {
					var httpUrl = eval($(this).attr('class')
						.substring(
							$(this).attr('class')
								.lastIndexOf('A_')));
					window.open(httpUrl, windowName, param);
				});
		});
function getParamsOfShareWindow(width, height) {
	return [
		'toolbar=0,status=0,resizable=1,width=' + width + ',height=' + height + ',left=',
			(screen.width - width) / 2, ',top=',
			(screen.height - height) / 2 ].join('');
}
}
//评论框编辑器   
$(function () {   
	$("#smiley").hide(500);   
	$("#comment-smiley").click(function(){   
		$("#smiley").toggle(500);   
	});   
});   
$(function () {   
	$("#editor_tools").hide(500);   
	$("#comment").click(function(){   
		$("#editor_tools").toggle(500);   
	});   
});   
$(function () {   
	$("#fontcolor").hide(500);   
	$("#font-color").click(function(){   
		$("#fontcolor").toggle(500);   
	});   
});   
$(function () {   
	$("#smiley").toggle();   
	$("#comment").click(function(){   
		$("#smiley").hide(500);   
	});   
});   
$(function () {   
	$("#fontcolor").toggle();   
	$("#comment").click(function(){   
		$("#fontcolor").hide(500);   
	});   
});   
$(function () {   
	$("#smiley").hide();   
	$("#comment").click(function(){   
	});   
});   
$(function () {   
	$("#fontcolor").hide();   
	$("#comment").click(function(){   
	});   
});   
$(function() {
    function addEditor(a, b, c) {
        if (document.selection) {
            a.focus();
            sel = document.selection.createRange();
            c ? sel.text = b + sel.text + c: sel.text = b;
            a.focus()
        } else if (a.selectionStart || a.selectionStart == '0') {
            var d = a.selectionStart;
            var e = a.selectionEnd;
            var f = e;
            c ? a.value = a.value.substring(0, d) + b + a.value.substring(d, e) + c + a.value.substring(e, a.value.length) : a.value = a.value.substring(0, d) + b + a.value.substring(e, a.value.length);
            c ? f += b.length + c.length: f += b.length - e + d;
            if (d == e && c) f -= c.length;
            a.focus();
            a.selectionStart = f;
            a.selectionEnd = f
        } else {
            a.value += b + c;
            a.focus()
        }
    }
    var g = document.getElementById('comment') || 0;
    var h = {
        strong: function() {
            addEditor(g, '<strong>', '</strong>')
        },
        em: function() {
            addEditor(g, '<em>', '</em>')
        },
        del: function() {
            addEditor(g, '<del>', '</del>')
        },
        underline: function() {
            addEditor(g, '<u>', '</u>')
        },
        quote: function() {
            addEditor(g, '<blockquote>', '</blockquote>')
        },
        ahref: function() {
            var a = prompt('请输入链接地址', 'http://');
            var b = prompt('请输入链接描述','');
            if (a) {
                addEditor(g, '<a target="_blank" href="' + a + '"rel="external">' + b + '</a>','')
            }
        },
        img: function() {
            var a = prompt('请输入图片地址', 'http://');
            if (a) {
                addEditor(g, '<img src="' + a + '" alt="" />','')
            }
        },
        private: function() {
            addEditor(g, '防偷看小补丁，阻止以一切名义查水表！[private]', '[/private]')
        },
        code: function() {
            addEditor(g, '<pre>', '</pre> ')
        },        red: function() {   
            addEditor(g, '<font color="red">', '</font>')   
        },   
        fuchsia: function() {   
            addEditor(g, '<font color="fuchsia">', '</font>')   
        },   
        purple: function() {   
           addEditor(g, '<font color="purple">', '</font>')   
        },   
        orange: function() {   
            addEditor(g, '<font color="orange">', '</font>')   
        },   
        yellow: function() {   
           addEditor(g, '<font color="yellow">', '</font>')   
        },   
        olive: function() {   
           addEditor(g, '<font color="olive">', '</font>')   
        },   
        lime: function() {   
           addEditor(g, '<font color="lime">', '</font>')   
        },   
        maroon: function() {   
           addEditor(g, '<font color="maroon">', '</font>')   
        },   
        aqua: function() {   
           addEditor(g, '<font color="aqua">', '</font>')   
        },   
        teal: function() {   
          addEditor(g, '<font color="teal">', '</font>')   
        },   
        green: function() {   
           addEditor(g, '<font color="green">', '</font>')   
        },   
        blue: function() {   
            addEditor(g, '<font color="blue">', '</font>')   
        },   
        navy: function() {   
            addEditor(g, '<font color="navy">', '</font>')   
        },   
        gray: function() {   
            addEditor(g, '<font color="gray">', '</font>')   
        },   
        deepskyblue: function() {   
            addEditor(g, '<font color="deepskyblue">', '</font>')   
        },   
		gold: function() {   
            addEditor(g, '<font color="gold">', '</font>')   
        },   		silver: function() {   
            addEditor(g, '<font color="silver">', '</font>')   
        },   
        black: function() {   
            addEditor(g, '<font color="black">', '</font>')   
        }
    };
    window['SIMPALED'] = {};
    window['SIMPALED']['Editor'] = h
});
//点击回复显示@用户名   
$('.reply').click(function() {    
var atid = '"#' + $(this).parent().attr("id") + '"';    
var atname = $(this).prevAll().find('strong:first').text();    
$("#comment").attr("value","<a href=" + atid + ">@" + atname + " </a>").focus();    
});    
$('.cancel-comment-reply a').click(function(){
$("#comment").attr("value",'');    
});    
});
// 链接复制
function copy_code(text) {
  if (window.clipboardData) {
    window.clipboardData.setData("Text", text)
	alert("已经成功将原文链接复制到剪贴板！");
  } else {
	var x=prompt('你的浏览器可能不能正常复制\n请您手动进行：',text);
  }
}