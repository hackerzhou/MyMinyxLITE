<?php
add_action('admin_menu', 'add_theme_settings');

function add_theme_settings() {
	add_theme_page('MyMinyxLITE 设置', 'MyMinyxLITE 设置', 'edit_themes', 'theme-settings.php', 'theme_settings');
}

if (isset($_REQUEST['page']) && ($_REQUEST['page'] == basename(__FILE__))) {
	global $themename, $shortname, $options;
	global $GOOGLE_ANALYTICS_ID_PARA, $GOOGLE_CSE_ID_PARA, $ENABLE_LAZY_LOAD_PARA
		, $ENABLE_FACEBOX_PARA, $PERSONAL_INFO_PARA, $JQUERY_SOURCE, $ENABLE_COMMENTS_MAIL_NOTIFY
		, $COMMENTS_EMAIL_FROM, $ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY, $THEME_OPTIONS_PREFIX, $ENABLE_MAKEITUP_COMMENT;
	if (isset($_POST['save'])) {
		save_theme_settings($GOOGLE_ANALYTICS_ID_PARA, filterUserInput($_REQUEST[$GOOGLE_ANALYTICS_ID_PARA]));
		save_theme_settings($GOOGLE_CSE_ID_PARA, filterUserInput($_REQUEST[$GOOGLE_CSE_ID_PARA]));
		save_theme_settings($ENABLE_LAZY_LOAD_PARA, filterUserInput($_REQUEST[$ENABLE_LAZY_LOAD_PARA]));
		save_theme_settings($ENABLE_FACEBOX_PARA, filterUserInput($_REQUEST[$ENABLE_FACEBOX_PARA]));
		save_theme_settings($ENABLE_MAKEITUP_COMMENT, filterUserInput($_REQUEST[$ENABLE_MAKEITUP_COMMENT]));
		save_theme_settings($PERSONAL_INFO_PARA, filterUserInput($_REQUEST[$PERSONAL_INFO_PARA]));
		save_theme_settings($JQUERY_SOURCE, filterUserInput($_REQUEST[$JQUERY_SOURCE]));
		save_theme_settings($ENABLE_COMMENTS_MAIL_NOTIFY, filterUserInput($_REQUEST[$ENABLE_COMMENTS_MAIL_NOTIFY]));
		save_theme_settings($COMMENTS_EMAIL_FROM, filterUserInput($_REQUEST[$COMMENTS_EMAIL_FROM]));
		save_theme_settings($ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY, filterUserInput($_REQUEST[$ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY]));
		wp_redirect(admin_url('themes.php?page=theme-settings.php&event=saveComplete'));
		die;
	} else if (isset($_POST['reset'])) {
		delete_option($THEME_OPTIONS_PREFIX . $GOOGLE_ANALYTICS_ID_PARA);
		delete_option($THEME_OPTIONS_PREFIX . $GOOGLE_CSE_ID_PARA);
		delete_option($THEME_OPTIONS_PREFIX . $ENABLE_LAZY_LOAD_PARA);
		delete_option($THEME_OPTIONS_PREFIX . $ENABLE_FACEBOX_PARA);
		delete_option($THEME_OPTIONS_PREFIX . $ENABLE_MAKEITUP_COMMENT);
		delete_option($THEME_OPTIONS_PREFIX . $PERSONAL_INFO_PARA);
		delete_option($THEME_OPTIONS_PREFIX . $JQUERY_SOURCE);
		delete_option($THEME_OPTIONS_PREFIX . $COMMENTS_EMAIL_FROM);
		delete_option($THEME_OPTIONS_PREFIX . $ENABLE_COMMENTS_MAIL_NOTIFY);
		delete_option($THEME_OPTIONS_PREFIX . $ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY);
		wp_redirect(admin_url('themes.php?page=theme-settings.php&event=resetComplete'));
		die;
	}
}

function filterUserInput($str) {
	return strip_tags(stripcslashes($str), "<b><i><div><span><style><img><li><ul><ol><table><tr><td><th><a><p><small><h1><h2><h3><h4><h5><h6>");
}

function save_theme_settings($name, $value) {
	global $THEME_OPTIONS_PREFIX;
	update_option($THEME_OPTIONS_PREFIX . $name, $value);
}

function print_select_component($id, $array) {
	echo "<select id=\"$id\" name=\"$id\">";
	for ($i = 0; $i < count($array); $i++) {
		$item = $array[$i];
		echo "<option value=\"" . $item["value"] . "\"" . ($item["value"] === get_theme_option($id, $array[0]) ? " selected=\"selected\"" : "") . ">" . $item["label"] . "</option>";
	}
	echo "</select>";
}

function print_input_component($id) {
	echo "<input id=\"$id\" name=\"$id\" type=\"text\" class=\"regular-text\" value=\"". get_theme_option($id, "") . "\"/>";
}

function theme_settings() {
	global $GOOGLE_ANALYTICS_ID_PARA, $GOOGLE_CSE_ID_PARA, $ENABLE_LAZY_LOAD_PARA
		, $ENABLE_FACEBOX_PARA, $PERSONAL_INFO_PARA, $JQUERY_SOURCE, $ENABLE_COMMENTS_MAIL_NOTIFY
		, $COMMENTS_EMAIL_FROM, $ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY, $ENABLE_MAKEITUP_COMMENT;
	$select_tf = array(
		array(
			"value" => "true",
			"label" => "启用（默认）&nbsp;"
		),
		array(
			"value" => "false",
			"label" => "禁用"
		)
	);
	$select_jquery_source = array(
		array(
			"value" => "Microsoft CDN",
			"label" => "Microsoft CDN（默认）"
		),
		array(
			"value" => "Google Ajax API CDN",
			"label" => "Google Ajax API CDN&nbsp;"
		),
		array(
			"value" => "jQuery CDN",
			"label" => "jQuery CDN"
		),
		array(
			"value" => "Local",
			"label" => "Local"
		)
	);
	?>
	<div class="wrap" style="width:680px;">
	<div id="icon-options-general" class="icon32"></div>
	<h2>MyMinyxLITE 主题设置</h2>
	<div id="message" class="updated fade">
	<p>本主题由 <a href="http://hackerzhou.me">hackerzhou</a> 根据 www.storelicious.com(www.spiga.com.mx) 的 MinyxLITE 2.0 主题扩展而成，修改UI以及添加一些自定义的功能，详见 <a href="http://wiki.hackerzhou.me/My_Minyx_LITE">Wiki Page</a>，如有问题，可以在 <a href="http://twitter.com/hackerzhou">Twitter</a> 以及 <a href="http://weibo.com/hackerzhou">新浪微博</a> 上@hackerzhou 询问。</p>
	<p>本作品采用 <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">知识共享署名-非商业性使用-相同方式共享 3.0</a>许可协议进行许可。</p>
	</div>
	<?php
	echo "<script type=\"text/javascript\" src=\"". get_jquery(). "\"></script>";
	if (isset($_GET['event'])) {
		if ("saveComplete" == $_GET["event"]) {
			echo "<div class=\"updated\" id=\"eventMsg\" style=\"opacity:0;text-align:center;\"><p>设置已保存</p></div>";
		} else if ("resetComplete" == $_GET["event"]) {
			echo "<div class=\"updated fade\" id=\"eventMsg\" style=\"opacity:0;text-align:center;\"><p>已恢复MyMinyxLITE的默认设置</p></div>";
		}
		echo "<script type=\"text/javascript\">$('#eventMsg').animate({opacity:1}, 2000);</script>";
	} 
	?>
	<form action="" method="post">
	<table class="form-table">
	<tr><th scope="row">Google Analytics ID：</th><td><?php print_input_component($GOOGLE_ANALYTICS_ID_PARA); ?>&nbsp;（留空禁用）</td></tr>
	<tr><th scope="row">Custom Search Engine ID：</th><td><?php print_input_component(GOOGLE_CSE_ID_PARA); ?>&nbsp;（留空禁用）</td></tr>
	<tr><th scope="row">图片jQuery Lazy Load效果：</th><td><?php print_select_component($ENABLE_LAZY_LOAD_PARA, $select_tf); ?></td></tr>
	<tr><th scope="row">图片启用Facebox效果：</th><td><?php print_select_component($ENABLE_FACEBOX_PARA, $select_tf); ?></td></tr>
	<tr><th scope="row">评论启用MakeItUp：</th><td><?php print_select_component($ENABLE_MAKEITUP_COMMENT, $select_tf); ?></td></tr>
	<tr><th scope="row">评论邮件通知功能：</th><td><?php print_select_component($ENABLE_COMMENTS_MAIL_NOTIFY, $select_tf); ?></td></tr>
	<tr class="comment_email_from_tr" style="display:none;"><th scope="row">评论邮件From地址：</th><td><?php print_input_component($COMMENTS_EMAIL_FROM); ?>&nbsp;（留空使用默认邮箱）</td></tr>
	<tr class="comment_email_from_tr" style="display:none;"><th scope="row">管理员回复启用邮件通知：</th><td><?php print_select_component($ENABLE_ADMIN_COMMENTS_MAIL_NOTIFY, $select_tf); ?></td></tr>
	<tr><th scope="row">jQuery源：</th><td><?php print_select_component($JQUERY_SOURCE, $select_jquery_source); ?></td></tr>
	<tr><th scope="row">侧栏个人说明：</th><td><textarea id="<?php echo $PERSONAL_INFO_PARA; ?>" name="<?php echo $PERSONAL_INFO_PARA; ?>" style="height:180px;width:430px;"><?php echo get_theme_option($PERSONAL_INFO_PARA, ""); ?></textarea><br/>（留空不显示，支持HTML）</td></tr>
	<tr><td colspan="2"><center><input id="save" name="save" type="submit" class="button-primary" value="保存设置" style="margin:0 20px;"/><input id="reset" name="reset" type="submit" class="button-primary" style="margin:0 20px;" value="恢复默认"/></center></td></tr>
	</table>
	</form>
	<script type="text/javascript">
		mail_notify_sub_item();
		function mail_notify_sub_item(){if($("#enable_mail_notify").val()=="true"){$(".comment_email_from_tr").show();}else{$(".comment_email_from_tr").hide();}}
		$("#enable_mail_notify").change(function(){mail_notify_sub_item()});
	</script>
	</div>
	<?php 
}

?>