<?php
/**
 * Template Name: 投稿
 * 作者：露兜
 * 博客：http://www.ludou.org/
 * 
 * 更新记录
 *  2010年09月09日 ：
 *  首个版本发布
 *  
 *  2011年03月17日 ：
 *  修正时间戳函数，使用wp函数current_time('timestamp')替代time()
 *  
 *  2011年04月12日 ：
 *  修改了wp_die函数调用，使用合适的页面title
 *  
 *  2013年01月30日 ：
 *  错误提示，增加点此返回链接
 *  
 *  2013年07月24日 ：
 *  去除了post type的限制；已登录用户投稿不用填写昵称、email和博客地址
 */
    
if( isset($_POST['tougao_form']) && $_POST['tougao_form'] == 'send') {
    global $wpdb;
    $current_url = 'http://你的投稿页面地址';   // 注意修改此处的链接地址

    $last_post = $wpdb->get_var("SELECT `post_date` FROM `$wpdb->posts` ORDER BY `post_date` DESC LIMIT 1");

    // 博客当前最新文章发布时间与要投稿的文章至少间隔120秒。
    // 可自行修改时间间隔，修改下面代码中的120即可
    // 相比Cookie来验证两次投稿的时间差，读数据库的方式更加安全
    if ( current_time('timestamp') - strtotime($last_post) < 120 ) {
        wp_die('您投稿也太勤快了吧，先歇会儿！<a href="'.$current_url.'">点此返回</a>');
    }
        
    // 表单变量初始化
    $name = isset( $_POST['tougao_authorname'] ) ? trim(htmlspecialchars($_POST['tougao_authorname'], ENT_QUOTES)) : '';
    $email =  isset( $_POST['tougao_authoremail'] ) ? trim(htmlspecialchars($_POST['tougao_authoremail'], ENT_QUOTES)) : '';
    $blog =  isset( $_POST['tougao_authorblog'] ) ? trim(htmlspecialchars($_POST['tougao_authorblog'], ENT_QUOTES)) : '';
    $title =  isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'], ENT_QUOTES)) : '';
    $category =  isset( $_POST['cat'] ) ? (int)$_POST['cat'] : 0;
    $content =  isset( $_POST['tougao_content'] ) ? trim(htmlspecialchars($_POST['tougao_content'], ENT_QUOTES)) : '';
    
    // 表单项数据验证
    if ( empty($name) || mb_strlen($name) > 20 ) {
        wp_die('昵称必须填写，且长度不得超过20字。<a href="'.$current_url.'">点此返回</a>');
    }
    
    if ( empty($email) || strlen($email) > 60 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
        wp_die('Email必须填写，且长度不得超过60字，必须符合Email格式。<a href="'.$current_url.'">点此返回</a>');
    }
    
    if ( empty($title) || mb_strlen($title) > 100 ) {
        wp_die('标题必须填写，且长度不得超过100字。<a href="'.$current_url.'">点此返回</a>');
    }
    
    if ( empty($content) || mb_strlen($content) > 3000 || mb_strlen($content) < 100) {
        wp_die('内容必须填写，且长度不得超过3000字，不得少于100字。<a href="'.$current_url.'">点此返回</a>');
    }
    
    $post_content = '昵称: '.$name.'<br />Email: '.$email.'<br />blog: '.$blog.'<br />内容:<br />'.$content;
    
    $tougao = array(
        'post_title' => $title, 
        'post_content' => $post_content,
        'post_category' => array($category)
    );


    // 将文章插入数据库
    $status = wp_insert_post( $tougao );
  
    if ($status != 0) { 
        // 投稿成功给博主发送邮件
        // somebody#example.com替换博主邮箱
        // My subject替换为邮件标题，content替换为邮件内容
        wp_mail("somebody#example.com","My subject","content");

        wp_die('投稿成功！感谢投稿！<a href="'.$current_url.'">点此返回</a>', '投稿成功');
    }
    else {
        wp_die('投稿失败！<a href="'.$current_url.'">点此返回</a>');
    }
} get_header() ;?>


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

<p><?php the_content(); ?>

<article class="article-content">
			<p><strong>感谢您的分享精神，但在提交之前，请先看看供稿提示:</strong></p>
<ul>
<li>优先欢迎原创首发文章，包括译文，如是转载文章，请注明文章出处和来源链接。</li>
<li><strong>只接受前端设计开发、前端软件工具等与本站内容相关的供稿。</strong></li>
<li>我们会认真审阅每一篇供稿，但无法保证每篇供稿都会被发布，具体将根据文章质量来决定。</li>
<li>请勿投递软文性质的文章；需要广告合作的话，请看<a href="http://www.daqianduan.com/advertisement/">这里</a>。</li>
<li><span style="color: #008080;">如有需求，您也可以邮件投稿：haozi@daqianduan.com</span></li>
</ul>
		</article>

<!-- 关于表单样式，请自行调整-->
<form class="ludou-tougao" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; $current_user = wp_get_current_user(); ?>">
    <div style="text-align: left; padding-top: 10px;">
        <label for="tougao_authorname">昵称:*</label>
        <input type="text" size="40" value="<?php if ( 0 != $current_user->ID ) echo $current_user->user_login; ?>" id="tougao_authorname" name="tougao_authorname" />
    </div>

    <div style="text-align: left; padding-top: 10px;">
        <label for="tougao_authoremail">E-Mail:*</label>
        <input type="text" size="40" value="<?php if ( 0 != $current_user->ID ) echo $current_user->user_email; ?>" id="tougao_authoremail" name="tougao_authoremail" />
    </div>
                    
    <div style="text-align: left; padding-top: 10px;">
        <label for="tougao_authorblog">您的博客:</label>
        <input type="text" size="40" value="<?php if ( 0 != $current_user->ID ) echo $current_user->user_url; ?>" id="tougao_authorblog" name="tougao_authorblog" />
    </div>

    <div style="text-align: left; padding-top: 10px;">
        <label for="tougao_title">文章标题:*</label>
        <input type="text" size="40" value="" id="tougao_title" name="tougao_title" />
    </div>

    <div style="text-align: left; padding-top: 10px;">
        <label for="tougaocategorg">分类:*</label>
        <?php wp_dropdown_categories('hide_empty=0&id=tougaocategorg&show_count=1&hierarchical=1'); ?>
    </div>
                    
    <div style="text-align: left; padding-top: 10px;">
        <label style="vertical-align:top" for="tougao_content">文章内容:*</label>
        <textarea rows="15" cols="55" id="tougao_content" name="tougao_content"></textarea>
    </div>
                    
    <br clear="all">
    <div style="text-align: center; padding-top: 10px;">
        <input type="hidden" value="send" name="tougao_form" />
        <button type="submit" value="提交" class="btn btn-primary"/>提交</button>
        <button type="reset" value="重填"  class="btn btn-primary"/>重填</button>
    </div>
</form></p>
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