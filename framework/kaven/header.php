<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/kaven.css" type="text/css">


<?php include('../../kaven/seo.php'); ?>
<?php wp_head();?>
</head>
<body>

<div id="rcframe"><?php include('../../kaven/list.php'); ?> </div>
<div id="header">


<?php if ( is_singular() ){ ?>
<div id="headbody" style="max-width:1110px">
<div id="hbleft"><a href="/" id="logo">
<img src="<?php bloginfo('template_url'); ?>/css/logo.png" height="40"></a></div>

<div class="clear"></div>
</div>
</div><div id="navfix">
<div id="navbody" style=" max-width: 1110px; ">


<div id="nav">
<a href="" id="navtags" class="yyicon2 yy2biao yynavsub"></a><div id="hbsearch">
<form method="get" id="searchform" action="/">
<input type="text" value="快速检索" id="sotext" name="s" autocomplete="off"><input type="submit" value="" id="soinput" class="yyicon yyso">
</form>
</div>
</div>
<div id="cats">
<a id="cative" href="/">全部</a>
	<?php wp_tag_cloud('smallest=12&largest=15&unit=px&number=10&orderby=count&order=DESC');?>


</div>
<div class="clear"></div>
</div>
<?php } ?>

<?php if ( !is_singular() ){ ?>
<div id="headbody">
<div id="hbleft"><a href="/" id="logo">
<img src="<?php bloginfo('template_url'); ?>/css/logo.png" height="40"></a></div>

<div class="clear"></div>
</div>
</div><div id="navfix">
<div id="navbody">

<div id="nav">
<a href="" id="navtags" class="yyicon2 yy2biao yynavsub"></a><div id="hbsearch">
<form method="get" id="searchform" action="/">
<input type="text" value="快速检索" id="sotext" name="s" autocomplete="off"><input type="submit" value="" id="soinput" class="yyicon yyso">
</form>
</div>
</div>

<div class="clear"></div>
<div id="cats">
<a id="cative" href="/">全部</a>
	<?php wp_tag_cloud('smallest=12&largest=15&unit=px&number=10&orderby=count&order=DESC');?>

</div>
<div class="clear"></div>
</div>
<?php } ?>


</div>
<div class="clear"></div>