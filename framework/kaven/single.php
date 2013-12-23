
<?php get_header() ;?>


<div id="container" >
<div id="workshow">
<div id="worktj">
<div class="worktitle">相关内容</div>
<div id="worktjbody">


   <?php
$post_num =3; // 设置调用条数
$args = array(
   'post_password' => ”,
   'post_status' => 'publish', // 只选公开的文章.
   'post__not_in' => array($post->ID),//排除当前文章
   'caller_get_posts' => 1, // 排除置頂文章.
   
   'posts_per_page' => $post_num );
$query_posts = new WP_Query();
$query_posts->query($args);
while( $query_posts->have_posts() ) { $query_posts->the_post(); ?>

<div class="scitem">
<a class="simage" href="<?php the_permalink() ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=180&w=280&zc=1" width="280" height="180" data-pinit="registered"></a>
<div class="simgbody">
<div class="simgh">
<a href="<?php the_permalink() ?>" class="simgtitle" target="_blank"><?php the_title(); ?></a>
</div>

<div class="clear"></div>
<div class="simgfoot"><div>
<span class="simgdate"></span>
<span><?php past_date() ?></span>
</div>
<p class="itemfoot"><a href="" class="yyicon2 yy2dian"></a>
<span class="iftxt"><?php post_views(' ', ''); ?></span><a href="" class="yyicon2 yy2xiaoxin"></a>
<span class="iftxt"><?php comments_popup_link ('抢沙发','1条评论','%条评论'); ?></span></p>
</div></div>
<div class="clear">
</div>
</div>
 <?php } wp_reset_query();?>



</div><div id="viewuser"><div class="worktitle">最近访客</div>
<ul>
<?php if (function_exists('zsofa_most_active_friends')) { echo zsofa_most_active_friends(24);} ?>

<div class="clear"></div>
</ul>

</div>

<div id="tjuser">
<div class="worktitle">猜你喜欢</div>
<ul>


   <?php
$post_num =6; // 设置调用条数
$args = array(
   'post_password' => ”,
   'post_status' => 'publish', // 只选公开的文章.
   'post__not_in' => array($post->ID),//排除当前文章
   'caller_get_posts' => 1, // 排除置頂文章.
   
   'posts_per_page' => $post_num );
$query_posts = new WP_Query();
$query_posts->query($args);
while( $query_posts->have_posts() ) { $query_posts->the_post(); ?>

<li><a href="<?php the_permalink() ?>" target="_blank" title="<?php the_title(); ?>"><img width="50" height="50" src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=50&w=50&zc=1"><p class="tju-s1"><?php the_title(); ?></p><p class="tju-s2"><?php past_date() ?></p></a></li>

 <?php } wp_reset_query();?>



<div class="clear"></div>
</ul>
</div>
<div class="clear"></div>
</div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="workcontent" style="margin-right: 350px;">
<div class="worktitle"><?php the_title(); ?></div>
<div id="workbody">

<p><?php the_content(); ?></p>
<div id="workqita">
<div id="workql">
<p class="worktags">

<?php if ( get_the_tags() ) { the_tags('标签：', '', ''); } else{ echo "本文暂无标签";  } ?>

</p>
<p>时间：<?php past_date() ?></p>
<p>访问：<?php post_views(' ', ''); ?></p>
<p>来源： <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_permalink() ?> | <?php bloginfo('name');?></a></p></div><div class="clear"></div></div>

<div id="workcomment">
<?php comments_template( '', true ); ?>
</div>
</div>
</div><?php endwhile; endif;?>
</div>
<div class="clear"></div>
</div>

<div class="clear"></div>

<?php get_footer() ;?></body></html>