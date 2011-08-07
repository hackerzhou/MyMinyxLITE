<?php
//Options Section
$THEME_OPTIONS_PREFIX = "MyMinyxLITE_";
$GOOGLE_ANALYTICS_ID_PARA = "ga_id";
$GOOGLE_CSE_ID_PARA = "gcse_id";
$ENABLE_LAZY_LOAD_PARA = "enable_ll";
$ENABLE_FACEBOX_PARA = "enable_fb";
$PERSONAL_INFO_PARA = "personal_info";
$ENABLE_COMMENTS_MAIL_NOTIFY = "enable_mail_notify";
$ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY = "enable_admin_mail_notify";
$JQUERY_SOURCE = "jquery_source";
$COMMENTS_EMAIL_FROM = "comment_email_from";
$ENABLE_MAKEITUP_COMMENT = "enable_makeitup_comment";

// add_action('switch_theme', 'my_switch_theme');
remove_action('do_feed_rss2', 'do_feed_rss2', 10, 1);
add_action('do_feed_rss2', 'my_do_feed_rss2', 10, 1);
add_action('after_setup_theme', 'myminyxlite_theme_setup');

function myminyxlite_theme_setup() {
	optimize_html_head();
	add_theme_support('automatic-feed-links');
	add_filter('the_content', 'facebox_filter', 7);
	load_comment_mail_notify_module();
	//Remove syntax highlighter plugin's version number
	add_filter('syntaxhighlighter_cssthemeurl', 'remove_version_in_url', 15, 1);
	add_filter('syntaxhighlighter_csscoreurl', 'remove_version_in_url', 15, 1);
	if (is_admin()) {
		require_once(TEMPLATEPATH . '/theme-settings.php');
	}
}

function get_theme_option($name, $default) {
	global $THEME_OPTIONS_PREFIX;
	return get_option($THEME_OPTIONS_PREFIX . $name, $default);
}

function optimize_html_head() {
	wp_deregister_script('l10n');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action('wp_head', 'start_post_rel_link', 10, 0 );
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	add_filter('the_generator', create_function('', 'return null;'));
	add_filter('script_loader_src', 'remove_version_in_url', 15, 1);
	add_filter('style_loader_src', 'remove_version_in_url', 15, 1);
}

/* Comment Mail Notification Module Start */
function load_comment_mail_notify_module() {
	if ((is_single() || is_page()) && is_comment_mail_notify_enable()) {
		add_action('comment_post', 'comment_mail_notify');
	}
}

function is_comment_mail_notify_enable() {
	global $ENABLE_COMMENTS_MAIL_NOTIFY;
	$enable = get_theme_option($ENABLE_COMMENTS_MAIL_NOTIFY, "true") !== "false";
	if ($enable && current_user_can('level_10')) {
		global $ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY;
		return (get_theme_option($ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY, "true") !== "false");
	}
	return true;
}

function is_makeitup_comment_enable() {
	global $ENABLE_MAKEITUP_COMMENT;
	return get_theme_option($ENABLE_MAKEITUP_COMMENT, "true") !== "false";
}

function get_from_email_address() {
	global $COMMENTS_EMAIL_FROM;
	$from_addr = get_theme_option($COMMENTS_EMAIL_FROM, "");
	if ($from_addr == "") {
		$from_addr = get_option('admin_email');
	}
	return $from_addr;
}

function need_notify($parent_id) {
	return get_comment_meta($parent_id, 'need_email_notify', true) == "1";
}

function comment_mail_notify($comment_id) {
	if (isset($_POST['email_notify'])) {
		add_comment_meta($comment_id, 'need_email_notify', '1', true);
	}
	$comment = get_comment($comment_id);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	$need_notify = ($parent_id != '') && ($comment->comment_approved != 'spam') && need_notify($parent_id);
	if ($need_notify) {
		$parent_comment_author = trim(get_comment($parent_id)->comment_author);
		$parent_comment_content = trim(get_comment($parent_id)->comment_content);
		$mail_from_addr = trim(get_from_email_address());
		$mail_to_addr = trim(get_comment($parent_id)->comment_author_email);
		$article_title = trim(get_the_title($comment->comment_post_ID));
		$comment_author = trim($comment->comment_author);
		$comment_content = trim($comment->comment_content);
		$comment_link = htmlspecialchars(get_comment_link($parent_id));
		$blog_name = html_entity_decode(get_option('blogname'), ENT_QUOTES);
		$blog_url = home_url();
		$blog_charset = get_option('blog_charset');
		$subject = "[$blog_name] 有人回复了您在“$article_title”的评论";
		$message = "
		<p>$parent_comment_author, 您好!</p>
		<p>您在《$article_title》的留言：<div style=\"margin-left:20px\">“$parent_comment_content”</div></p>
		<p>有 $comment_author 给您的回复：<div style=\"margin-left:20px\">“$comment_content”</div></p>
		<p>您可以<a href=\"$comment_link\">查看回复的完整内容</a>。</p>
		<p>欢迎经常访问 <a href=\"$blog_url\">$blog_name</a></p>
		<p>（此邮件为系统自动发送，请勿回复。）</p>";
		$headers = "From: $blog_name <$mail_from_addr>\nContent-Type: text/html; charset=\"$blog_charset\"\n";
		wp_mail($mail_to_addr, $subject, $message, $headers);
	}
}
/* Comment Mail Notification Module End */

function get_personal_info() {
	global $PERSONAL_INFO_PARA;
	return get_theme_option($PERSONAL_INFO_PARA, "");
}

function get_jquery() {
	global $JQUERY_SOURCE;
	$jquery = get_theme_option($JQUERY_SOURCE, "");
	switch ($jquery) {
		case "Microsoft CDN": return "http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.6.1.min.js";
		case "Google Ajax API CDN": return "http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js";
		case "jQuery CDN": return "http://code.jquery.com/jquery-1.6.1.min.js";
		default: return the_recource_url("/jquery-1.6.1.min.js", false);
	}
}

function the_recource_url($theme_relative_url, $print = true) {
	if ($print) {
		echo get_stylesheet_directory_uri() . $theme_relative_url;
	} else {
		return get_stylesheet_directory_uri() . $theme_relative_url;
	}
}

function remove_version_in_url($src) {
	$parts = explode('?', $src);
	return $parts[0];
}

function facebox_filter($content) {
	if (!is_need_facebox_effect()) {
		return $content;
	}
	$new_content = preg_replace_callback("/(<a)([^>]+)(>[^<]*<img[^>]+>[^<]*<\\/a>)/", "facebox_filter_replace_callback", $content);
	if (strlen($content) != strlen($new_content)) {
		add_action('wp_footer', 'facebox_plugin');
	}
	return $new_content;
}

function facebox_filter_replace_callback($matches) {
	$partA = $matches[2];
	return $matches[1] . " class=\"faceboxImg\"" . $matches[2] . $matches[3];
}

function is_need_facebox_effect() {
	global $ENABLE_FACEBOX_PARA;
	return (is_single() || is_page()) 
		&& (get_theme_option( $ENABLE_FACEBOX_PARA, "true") !== "false");
}

function is_need_lazy_load_effect() {
	global $ENABLE_LAZY_LOAD_PARA;
	return (is_single() || is_page()) 
		&& (get_theme_option($ENABLE_LAZY_LOAD_PARA, "true") !== "false");
}

function get_cse_id_if_enable() {
	global $GOOGLE_CSE_ID_PARA;
	$cse_id = get_theme_option($GOOGLE_CSE_ID_PARA, "");
	if (strlen($cse_id) > 0) {
		return $cse_id;
	} else {
		return false;
	}
}

function the_license_information() {
  	if(is_single() || is_page()) {
  		echo "<div id=\"license_information\">";
  		echo "<div id=\"cc_Img\">" . get_cc_copyright_img() . "</div>";
        echo "<div id=\"copyrightText\">" . get_cc_copyright() . "</div>";
        echo "</div>";
  	}
}

function the_google_analytics_if_enable() {
	global $GOOGLE_ANALYTICS_ID_PARA;
	$ga_id = get_theme_option($GOOGLE_ANALYTICS_ID_PARA, "");
	if (strlen($ga_id) > 0) {
?>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $ga_id; ?>']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<?php
	}
}

function facebox_plugin() {
	?>
		<link rel="stylesheet" type="text/css" href="<?php the_recource_url('/facebox.css') ?>" media="screen" />
		<script type="text/javascript" src="<?php the_recource_url('/facebox.js') ?>"></script>
		<script type="text/javascript">$(function() {$.facebox.settings.loadingImage='<?php the_recource_url('/pix/loading.gif') ?>';$.facebox.settings.closeImage='<?php the_recource_url('/pix/closelabel.png') ?>';$('a.faceboxImg').facebox();});</script>
	<?php
}

/**
 * This function is a customize version for /wp-includes/functions.php
 *
 * @param bool $for_comments True for the comment feed, false for normal feed.
 */
function my_do_feed_rss2( $for_comments ) {
	$currentTheme = get_theme(get_current_theme());
	$currentThemeAbsDir = $currentTheme["Template Dir"];
	if ( $for_comments )
		load_template( ABSPATH . WPINC . '/feed-rss2-comments.php' );
	else
		load_template( $currentThemeAbsDir . '/feed-rss2.php' );
}

function get_content($avoidPluginFilters = false, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '')
{
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	if ($avoidPluginFilters) {
		$content = wptexturize($content);
		$content = convert_smilies($content);
		$content = convert_chars($content);
		$content = wpautop($content);
		$content = shortcode_unautop($content);
		$content = prepend_attachment($content);
	} else {
		$content = apply_filters('the_content', $content);
	}
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

function seo_title(){
	wp_title( '|', true, 'right' );
	bloginfo('name');
	if(is_paged()){
		global $paged;
		echo " - 第 " . $paged . " 页";
	}
}

function my_abstract(){
	 $postContent = get_content(true);
     $postLink = get_permalink();
     $postTitle = get_the_title('', '', false);
     $excerpt = null;
	 $array = explode("</p>", $postContent);
	 for($i = 0; $i < count($array); $i++) {
	     if($array[$i] && trim($array[$i]) != "") {
	     	$excerpt = strip_tags($array[$i], "<br><div><a>");
	     	break;
	     }
	 }
     $excerpt = "<p>" . $excerpt . " <a style=\"white-space:nowrap;\" title=\"阅读文章：". $postTitle ."\" href=\"" . $postLink . "\" rel=\"nofollow\">[阅读全文]</a></p>";
     echo $excerpt;
}

function get_copyright(){
	return "Copyright &copy; " . date('Y') . " <a href=\"" . home_url() . "\">" . get_bloginfo('name') . "</a>  2009-" . date('Y');
}

function get_cc_copyright(){
	return "本文基于 <a href=\"http://creativecommons.org/licenses/by/2.5/cn/\">署名 2.5 中国大陆</a> 许可协议发布，欢迎转载，演绎或用于商业目的，但是必须保留本文的署名 <a href=\""
	 . get_author_posts_url(get_the_author_meta('ID')) . "\">" . get_the_author() . "</a> 并包含 <a href=\"" . get_permalink() . "\">原文链接</a>。";
}

function get_cc_copyright_img(){
	return "<a rel=\"license\" href=\"http://creativecommons.org/licenses/by/2.5/cn/\" class=\"ccImg\" title=\"Creative Commons License\" target=\"_blank\"></a>";
}

function native_pagenavi(){
    global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'type' => 'plain',
		'prev_text' => "上一页",
    	'next_text' => "下一页",
		'end_size' => 1,
    	'mid_size' => 8
		);
		
	if($wp_rewrite->using_permalinks()) {
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	}
	
	if(!empty($wp_query->query_vars['s'])) {
		$pagination['add_args'] = array('s' => get_query_var( 's' ));
	}
	echo paginate_links($pagination);
}

function wp_get_old_archives($monthCount){
	$temp = wp_get_archives('type=monthly&echo=0&show_post_count=true');
	$tempArray = explode("\n", $temp);
	$length = count($tempArray);
	$result = '';
	if($length > $monthCount){
		for($i = $monthCount;$i < $length; $i++){
			$result .= $tempArray[$i];
		}
	}
	return $result;
}

function the_page_title() {
	if (is_category()) {
		echo "<h2 class=\"pagetitle\">分类目录：" . single_cat_title("", false) . "</h2>";
	} else if (is_day()) {
		echo "<h2 class=\"pagetitle\">文章归档：" . get_the_time('Y年m月d日') . "</h2>";
	} else if (is_month()) {
		echo "<h2 class=\"pagetitle\">文章归档：" . get_the_time('Y年m月') . "</h2>";
	} else if (is_year()) {
		echo "<h2 class=\"pagetitle\">文章归档：" . get_the_time('Y年') . "</h2>";
	} else if (is_search()) {
		if (get_cse_id_if_enable() !== false || have_posts()) {
			echo "<h2 class=\"pagetitle\">“" . stripcslashes($_GET["s"]) . "” 的搜索结果</h2>";
		} else {
			echo "<h2 class=\"pagetitle\">很抱歉，找不到关于“" . stripcslashes($_GET["s"]) . "” 的文章</h2>";
		}
	} else if (!have_posts()) {
		echo "<h2 class=\"pagetitle\">您要访问的资源不存在或由于种种原因暂时不可见！</h2>";
	}
}

/* Temporary not used start */
function remove_absolute_url($url) {
	return str_ireplace(get_site_base_url(), '', strtolower($url));
}

function get_site_base_url() {
	if (!empty($_SERVER['SERVER_PROTOCOL'])) {
		$protocol = $_SERVER['SERVER_PROTOCOL'];
	} else if (!empty($_SERVER['HTTP_VERSION'])) {
		$protocol = $_SERVER['HTTP_VERSION'];
	}
	$protocol = strpos($protocol,'HTTPS') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'];
	$base_url = $protocol . "://" . $host;
	if (($_SERVER['SERVER_PORT'] != 80 && $protocol == 'http')
		|| ($_SERVER['SERVER_PORT'] != 443 && $protocol == 'https')) {
		$base_url.= ':' . $_SERVER['SERVER_PORT'];
	}
	return strtolower($base_url);
}
/* Temporary not used end */

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
<div id="comment-<?php comment_ID(); ?>">
<div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
         <div class="authorInfo">
         <?php printf('<cite class="fn">%s</cite><br/>', get_comment_author_link()) ?>
         <?php printf('%1$s %2$s', get_comment_date("Y-m-d"),  get_comment_time("H:i")) ?>
         </div>
</div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em> <br />
      <?php endif; ?>
      <?php comment_text() ?>
      <div class="replyEdit">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
         <?php edit_comment_link('[编辑]','  ',''); ?>
      </div>
</div>
<?php
}
?>