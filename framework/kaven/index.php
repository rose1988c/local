
<?php get_header() ;?>
<div id="container">
<div id="main">


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
<div class="scitem">
<a class="simage" href="<?php the_permalink(); ?>" target="_blank">
<img src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=180&w=280&zc=1" width="280" height="180" data-pinit="registered"></a>
<div class="simgbody">
<div class="simgh">
<a href="<?php the_permalink(); ?>" class="simgtitle" target="_blank"><?php the_title(); ?></a>
</div>
<div class="clear"></div>
<div class="simgfoot">
<div>
<span class="simgdate"></span>
<span><?php past_date() ?></span>
</div>
<p class="itemfoot"><a href="" class="yyicon2 yy2dian"></a><span class="iftxt"><?php post_views(' ', ''); ?></span><a href="" class="yyicon2 yy2xiaoxin"></a><span class="iftxt"><?php comments_popup_link ('抢沙发','1条评论','%条评论'); ?></span></p>
</div></div>
<div class="clear"></div>
</div>
<?php endwhile; ?>
	<?php else : ?>
	<p>这里好像什么文章都没有!~</p>
	<div class="b2"></div>
	<?php endif; ?>






<div class="clear"></div>
<div id="pages">
 <?php pagenavi(5); ?>
</div>


</div>
</div>
<div class="clear"></div>
<?php get_footer() ;?>

</body>
</html>