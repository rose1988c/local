<?php
add_filter( 'show_admin_bar', '__return_false' );

function dm_strimwidth($str ,$start , $width ,$trimmarker ){$output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str); return $output.$trimmarker;};
if ( function_exists('register_nav_menus') ) {
    register_nav_menus(array(
         'menu' => '头部菜单',
	
    ));
}
add_filter('show_admin_bar', '__return_false');



//评论回复邮件通知（所有回复都邮件通知）
function comment_mail_notify($comment_id) {
  $comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); //e-mail 发出点, no-reply 可改为可用的 e-mail.
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
    $message = '
<div style="background-color:#fff; border:1px solid #666666; color:#111;
-moz-border-radius:8px; -webkit-border-radius:8px; -khtml-border-radius:8px;
border-radius:8px; font-size:12px; width:702px; margin:0 auto; margin-top:10px;
font-family:微软雅黑, Arial;">
<div style="background:#666666; width:100%; height:60px; color:white;
-moz-border-radius:6px 6px 0 0; -webkit-border-radius:6px 6px 0 0;
-khtml-border-radius:6px 6px 0 0; border-radius:6px 6px 0 0; ">
<span style="height:60px; line-height:60px; margin-left:30px; font-size:12px;">
您在<a style="text-decoration:none; color:#00bbff;font-weight:600;"
href="' . get_option('home') . '">' . get_option("blogname") . '
</a>博客上的留言有回复啦！</span></div>
<div style="width:90%; margin:0 auto">
<p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
<p>您曾在 [' . get_option("blogname") . '] 的文章
《' . get_the_title($comment->comment_post_ID) . '》 上发表评论:
<p style="background-color: #EEE;border: 1px solid #DDD;
padding: 20px;margin: 15px 0;">' . nl2br(get_comment($parent_id)->comment_content) . '</p>
<p>' . trim($comment->comment_author) . ' 给您的回复如下:
<p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;
margin: 15px 0;">' . nl2br($comment->comment_content) . '</p>
<p>您可以点击 <a style="text-decoration:none; color:#00bbff"
href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整內容</a></p>
<p>欢迎再次光临 <a style="text-decoration:none; color:#00bbff"
href="' . get_option('home') . '">' . get_option("blogname") . '</a></p>
<p>(此邮件由系统自动发出, 请勿回复.)</p>
</div>
</div>';
    $message = convert_smilies($message);
    $from = "From: \"" . htmlspecialchars(get_option('blogname'),ENT_QUOTES) . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
add_action('comment_post', 'comment_mail_notify');

// 评论回复&头像缓存
function ATheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
global $commentcount,$wpdb, $post;
     if(!$commentcount) { //初始化楼层计数器
          $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
          $cnt = count($comments);//获取主评论总数量
          $page = get_query_var('cpage');//获取当前评论列表页码
          $cpp=get_option('comments_per_page');//获取每页评论显示数量
         if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
             $commentcount = $cnt + 1;//如果评论只有1页或者是最后一页，初始值为主评论总数
         } else {
             $commentcount = $cpp * $page + 1;
         }
     }
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
   <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
      <?php $add_below = 'div-comment'; ?>
		<div class="comment-author vcard"><?php if (get_option('swt_type') == 'Display') { ?>
			<?php
				$p = 'avatar/';
				$f = md5(strtolower($comment->comment_author_email));
				$a = $p . $f .'.jpg';
				$e = ABSPATH . $a;
				if (!is_file($e)){ //当头像不存在就更新
				$d = get_bloginfo('wpurl'). '/avatar/default.jpg';
				$s = '40'; //头像大小 自行根据自己模板设置
				$r = get_option('avatar_rating');
				$g = 'http://www.gravatar.com/avatar/'.$f.'.jpg?s='.$s.'&d='.$d.'&r='.$r;
                $avatarContent = file_get_contents($g);
                file_put_contents($e, $avatarContent);
				if ( filesize($e) == 0 ){ copy($d, $e); }
				};
			?>
			<img src='<?php bloginfo('wpurl'); ?>/<?php echo $a ?>' alt='' class='avatar' />
                <?php { echo ''; } ?>
			<?php } else { echo get_avatar( $comment, 40 );} ?>
					<div class="floor"><?php
 if(!$parent_id = $comment->comment_parent){
   switch ($commentcount){
     case 2 :echo "沙发";--$commentcount;break;
     case 3 :echo "板凳";--$commentcount;break;
     case 4 :echo "地板";--$commentcount;break;
     default:printf('%1$s楼', --$commentcount);
   }
 }
 ?>
         </div><?php get_author_class($comment->comment_author_email,$comment->comment_author_url)?>
<strong><?php comment_author_link() ?></strong>

&nbsp;&nbsp;<span class="edit_comment"><?php edit_comment_link('[编辑]','&nbsp;&nbsp;',''); ?></span></div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<span style="color:#f00; font-style:inherit">您的评论正在等待审核中...</span>
			<br />			
		<?php endif; ?>
		<?php comment_text() ?>
		<div class="clear"></div><span class="datetime"><?php ATheme_time_diff( $time_type = 'comment' ); ?></span><span class="reply">&nbsp;<?php comment_reply_link(array_merge( $args, array('reply_text' => '回复一下又不会怀孕~', 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
  </div>
<?php
}
function ATheme_end_comment() {
		echo '</li>';
}
//防查水表
function private_content($atts, $content = null) 
{  	if (current_user_can('create_users'))  		
  	return '<div class="private-content">' . $content . '</div>';return '<span style="background-color: #ffff00; color: #666699;">【已被史上最无耻最无敌防查水表专用功能屏蔽了】</span><br>';  }  
add_shortcode('private', 'private_content');
add_filter('comment_text', 'do_shortcode');

//获取访客VIP样式
function get_author_class($comment_author_email,$comment_author_url){
global $wpdb;
$adminEmail = 'YipChaoJun@Gmail.com';
$author_count = count($wpdb->get_results(
"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
if($comment_author_email ==$adminEmail)
echo '<a class="vip7" title="亲、你猜猜这是谁~"></a><a class="vp" href="mailto:YipChaoJun@Gmail.com" title="LinsKy主人"></a>';
$linkurls = $wpdb->get_results(
"SELECT link_url FROM $wpdb->links WHERE link_url = '$comment_author_url'");
if($author_count>=1 && $author_count<5 && $comment_author_email!=$adminEmail)
echo '<a class="vip1" title="亲、This is a 不明情况的围观群众"></a>';
else if($author_count>=5 && $author_count<15 && $comment_author_email!=$adminEmail)
echo '<a class="vip2" title="亲、This is a 有爱的围观群众"></a>';
else if($author_count>=15 && $author_count<30 && $comment_author_email!=$adminEmail)
echo '<a class="vip3" title="亲、This is a 热心的围观群众"></a>';
else if($author_count>=30 && $author_count<50 && $comment_author_email!=$adminEmail)
echo '<a class="vip4" title="亲、This is a 超级无敌的嘉宾"></a>';
else if($author_count>=50 &&$author_count<80 && $comment_author_email!=$adminEmail)
echo '<a class="vip5" title="亲、This is a 次世纪的元谋人"></a>';
else if($author_count>=80 && $author_coun<200 && $comment_author_email!=$adminEmail)
echo '<a class="vip6" title="亲、This is a 超人的类似物"></a>';
else if($author_count>=200 && $comment_author_email!=$adminEmail)
echo '<a class="vip7" title="亲、This is a 无敌大超人+无敌金刚兽坐骑"></a>';

foreach ($linkurls as $linkurl) {
if ($linkurl->link_url == $comment_author_url )
echo '<a class="vip" target="_blank" href="/links/" title="哟！隔壁邻居的呢！"></a>';
}
}		
//日志与评论的相对时间显示
function ATheme_time_diff( $time_type ) {
    switch( $time_type ){
        case 'comment':    //如果是评论的时间
            $time_diff = current_time('timestamp') - get_comment_time('U');
            if( $time_diff <= 86400 )    //24 小时之内
                echo human_time_diff(get_comment_time('U'), current_time('timestamp')).' 之前';    //显示格式 OOXX 之前
            else
                printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());    //显示格式 X年X月X日 OOXX 时
            break;
        case 'post';    //如果是日志的时间
            $time_diff = current_time('timestamp') - get_the_time('U');
            if( $time_diff <= 86400 )
                echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
            else
                the_time('Y-m-d H:i');
            break;
    }
}

//显示最近评论次数
function ATheme_WelcomeCommentAuthorBack($email = ''){
	if(empty($email)){
		return;
	}
	global $wpdb;
	$past_30days = gmdate('Y-m-d H:i:s',((time()-(24*60*60*30))+(get_option('gmt_offset')*3600)));
	$sql = "SELECT count(comment_author_email) AS times FROM $wpdb->comments
					WHERE comment_approved = '1'
					AND comment_author_email = '$email'
					AND comment_date >= '$past_30days'";
	$times = $wpdb->get_results($sql);
	$times = ($times[0]->times) ? $times[0]->times : 0;
	$message = $times ? sprintf(__('过去30天内您评论了<strong>%1$s</strong>次，感谢关注~' ), $times) : '您很久都没有留言了，这次想说点什么吗？';
	return $message;
}

// post thumbnail support 缩略图支持
//添加特色缩略图支持
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');
 
//输出缩略图地址 From wpdaxue.com
function post_thumbnail_src(){
    global $post;
	if( $values = get_post_custom_values("thumb") ) {	//输出自定义域图片地址
		$values = get_post_custom_values("thumb");
		$post_thumbnail_src = $values [0];
	} elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
    } else {
		$post_thumbnail_src = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$post_thumbnail_src = $matches [1] [0];   //获取该图片 src
		if(empty($post_thumbnail_src)){	//如果日志中没有图片，则显示随机图片
			$random = mt_rand(1, 10);
			echo get_bloginfo('template_url');
			echo '/img/no-thum.jpg';
			//如果日志中没有图片，则显示默认图片
			//echo '/images/default_thumb.jpg';
		}
	};
	echo $post_thumbnail_src;
}
//评论表情路径
add_filter('smilies_src','ATheme_custom_smilies_src',1,10);
function ATheme_custom_smilies_src ($img_src, $img, $siteurl) {
     return get_bloginfo('template_directory').'/images/smiley/'.$img;
}

function past_date() {
	global $post;
	$suffix = ' 前';
	$day = ' 天';
	$hour = ' 小时';
	$minute = ' 分钟';
	$second = ' 秒';
	$m = 60;
	$h = 3600;
	$d = 86400;
	$post_time = get_post_time('G', true, $post);
	$past_time = time() - $post_time;
	if ($past_time < $m) {
		$past_date = $past_time . $second;
	} else if ($past_time < $h) {
		$past_date = $past_time / $m;
		$past_date = floor($past_date);
		$past_date .= $minute;
	} else if ($past_time < $d) {
		$past_date = $past_time / $h;
		$past_date = floor($past_date);
		$past_date .= $hour;
	} else if ($past_time < $d * 30) {
		$past_date = $past_time / $d;
		$past_date = floor($past_date);
		$past_date .= $day;
	} else {
		the_time('d,m,Y');
		return;
	} 
	echo $past_date . $suffix;
	}
	add_filter('past_date', 'past_date');
add_theme_support( 'post-formats', array( 'status','aside','audio') );


// enable threaded comments
	function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
	}
	add_action('get_header', 'enable_threaded_comments');	
	// removes detailed login error information for security 移除wordpress登陆漏洞
	add_filter('login_errors',create_function('$a', "return null;"));
		
	//禁用半角符号自动转换为全角
	remove_filter('the_content', 'wptexturize');
		
	// 只搜索文章，排除页面
	add_filter('pre_get_posts','search_filter');
	function search_filter($query) {
	if ($query->is_search) {$query->set('post_type', 'post');}
	return $query;}	
	
	// 新窗口打开评论链接
	function hu_popuplinks($text) {
		$text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank'>", $text);
		return $text;
	}
	add_filter('get_comment_author_link', 'hu_popuplinks', 6);	
	
//访问计数
function record_visitors(){
	if (is_singular()) {global $post;
	 $post_ID = $post->ID;
	  if($post_ID) 
	  {
		  $post_views = (int)get_post_meta($post_ID, 'views', true);
		  if(!update_post_meta($post_ID, 'views', ($post_views+1))) 
		  {
			add_post_meta($post_ID, 'views', 1, true);
		  }
	  }
	}
}
add_action('wp_head', 'record_visitors');  
function post_views($before = '(点击 ', $after = ' 次)', $echo = 1)
{
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  if ($echo) echo $before, number_format($views), $after;
  else return $views;
};

  //翻页导航
function pagenavi($range = 9){
	global $paged, $wp_query;
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' id='pageprev'><span></span></a>";}
	
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " id='pageactive'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " id='pageactive'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " id='pageactive'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " id='pageactive'";echo ">$i</a>";}}
	
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' id='pagenext' title=''><span></span></a>";}
    }
}

function zsofa_most_active_friends($friends_num = 10) {
    global $wpdb;
    $counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 1 MONTH ) AND user_id='0' AND comment_author != 'zwwooooo' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author ORDER BY cnt DESC LIMIT $friends_num");
    foreach ($counts as $count) {
    $c_url = $count->comment_author_url;
    if ($c_url == '') $c_url = get_bloginfo('url');
    $mostactive .= '<li>' . '<a href="'. $c_url . '" title="' . $count->comment_author . ' ('. $count->cnt . 'comments">' . get_avatar($count->comment_author_email, 32) . '</a></li>';
    }
    return $mostactive;
}

//连接数量
$match_num_from = 1; //一个关键字少于多少不替换
$match_num_to = 5; //一个关键字最多替换
//连接到WordPress的模块
add_filter('the_content','tag_link',1);
//按长度排序
function tag_sort($a, $b){
if ( $a->name == $b->name ) return 0;
return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
//改变标签关键字
function tag_link($content){
global $match_num_from,$match_num_to;
$posttags = get_the_tags();
if ($posttags) {
usort($posttags, "tag_sort");
foreach($posttags as $tag) {
$link = get_tag_link($tag->term_id);
$keyword = $tag->name;
//连接代码
$cleankeyword = stripslashes($keyword);
$url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('View all posts in %s'))."\"";
$url .= 'target="_blank"';
$url .= ">".addcslashes($cleankeyword, '$')."</a>";
$limit = rand($match_num_from,$match_num_to);
//不连接的代码
$content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
$cleankeyword = preg_quote($cleankeyword,'\'');
$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
$content = preg_replace($regEx,$url,$content,$limit);
$content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
}
}
return $content;
}



//移除头部多余信息
remove_action('wp_head','wp_generator');//禁止在head泄露wordpress版本号
remove_action('wp_head','rsd_link');//移除head中的rel="EditURI"
remove_action('wp_head','wlwmanifest_link');//移除head中的rel="wlwmanifest"
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink 
remove_action('wp_head', 'rel_canonical' );

//禁用半角符号自动转换为全角
remove_filter('the_content', 'wptexturize');

//评论跳转链接添加nofollow
function ATheme_nofollow_compopup_link() {
  return' rel="nofollow"';
}
add_filter('comments_popup_link_attributes','ATheme_nofollow_compopup_link');

//阻止站内文章pingback
function ATheme_no_self_ping( &$links ) {
$home = get_option( 'home' );
foreach ( $links as $l => $link )
if ( 0 === strpos( $link, $home ) )
unset($links[$l]);
}
add_action( 'pre_ping', 'ATheme_no_self_ping' );

//wordpress文章里url生成超链接
add_filter('the_content', 'make_clickable');

//去除评论url超链接
remove_filter('comment_text', 'make_clickable', 9); 

//禁止自动保存和修改历史记录
add_action('wp_print_scripts', 'no_autosave');
remove_action('pre_post_update','wp_save_post_revision');
function no_autosave() {
  wp_deregister_script('autosave');
}

//添加编辑器快捷按钮
add_action('admin_print_scripts', 'ATheme_my_quicktags');
function ATheme_my_quicktags() {
    wp_enqueue_script(
        'my_quicktags',
        get_stylesheet_directory_uri().'/js/my_quicktags.js',
        array('quicktags')
    );
}

?>