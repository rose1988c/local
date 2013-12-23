<?php
/*
Template Name: Reader wall
*/
?>
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
<a class="simage" href="<?php the_permalink() ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=180&w=200&zc=1" width="280" height="180" data-pinit="registered"></a>
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
<span class="iftxt">9</span></p>
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
<!-- start 读者墙  Edited By iSayme-->
<?php
 
    $query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 24 MONTH ) AND user_id='0' AND comment_author_email != '改成你的邮箱账号' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT 39";//大家把管理员的邮箱改成你的,最后的这个39是选取多少个头像，大家可以按照自己的主题进行修改,来适合主题宽度
 
    $wall = $wpdb->get_results($query);
 
    $maxNum = $wall[0]->cnt;
 
    foreach ($wall as $comment)
 
    {
 
        $width = round(40 / ($maxNum / $comment->cnt),2);//此处是对应的血条的宽度
 
        if( $comment->comment_author_url )
 
        $url = $comment->comment_author_url;
 
        else $url="#";
  $avatar = get_avatar( $comment->comment_author_email, $size = '36', $default = get_bloginfo('wpurl').'/avatar/default.jpg' );
 
        $tmp = "<li><a target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<em>".$comment->comment_author."</em> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";
 
        $output .= $tmp;
 
     }
 
    $output = "<ul class=\"readers-list\">".$output."</ul>";
 
    echo $output ;
 
?>
 
<!-- end 读者墙 -->
</div><?php endwhile; endif;?>
</div>
<div class="clear"></div>
</div>

<div class="clear"></div>

<?php get_footer() ;?></body></html>