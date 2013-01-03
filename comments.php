<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>
<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
	setcookie("comm_verify", rand(0, getrandmax())."", time() + 3600 * 24, "/");
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
<div id="comments">
<div id="commentsTitle">
<?php if (!('open' == $post-> comment_status)) : ?>
<span class="closecomment">评论被关闭</span>
<?php else : ?>
<a href="javascript:postComments();" class="addcomment">发表评论</a>
<?php endif; ?>
<div id="comments-title"><div class="icon"></div><h3>本文<?php comments_number('暂无评论', '有 1 条评论', '有 % 条评论' );?></h3></div>
</div>
<ol class="commentlist">
  <?php
	wp_list_comments('type=comment&reply_text=[回复]&callback=mytheme_comment');
	?>
</ol>
<?php if (get_comment_pages_count() > 1 && get_option('page_comments' )) : ?>
	<div class="navigation"><?php paginate_comments_links('prev_text=上一页&next_text=下一页'); ?></div>
<?php endif; ?>

<?php else : // this is displayed if there are no comments so far ?>

<?php if ('open' == $post->comment_status) : ?>
<div class="nocommentsadd"><div class="nocommentsadd_icon"></div><div class="comments_status">本文暂时还没有评论，你可以<a href="javascript:postComments();">抢沙发</a>哟。</div></div>
<?php else : // comments are closed ?>
<div class="nocomments"><?php _e('Comments are closed','minyx2Lite')?>.</div>
<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>
<div id="respond">
<div id="commentFormWrapper">
<div id="respondTitleWrapper">
<div class="icon"></div><h3 id="respondTitle">发表评论</h3>
<div id="cancel-comment-reply"><a rel="nofollow" id="cancel-comment-reply-link" href="#respond">取消回复</a></div>
</div>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>您必须 <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">登录</a> 才能发表评论。</p>
<?php else : ?>
<form action="<?php echo get_template_directory_uri(); ?>/comments-ajax.php" method="post" id="commentform" onsubmit="onCommSubmit();">
  <?php if ( $user_ID ) : ?>
  <p style="margin-left:8px;">登录为 <a href="<?php echo home_url(); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="退出">退出 &raquo;</a></p>
  <?php else :
  			$arg0 = comments_field_name($id, 0);
  			$arg1 = comments_field_name($id, 1);
  			$arg2 = comments_field_name($id, 2);
  			$arg3 = comments_field_name($id, 3);
  ?>
  <p>
    <input type="text" name="<?php echo $arg0; ?>" class="comm_input input_0" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
    <label for="<?php echo $arg0; ?>">昵称 (必填)</label>
  </p>
  <p>
    <input type="text" name="<?php echo $arg1; ?>" class="comm_input input_1" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
    <label for="<?php echo $arg1; ?>">邮箱 (必填，仅用于生成<a href="www.gravatar.com">Gavatar</a>头像<?php echo is_comment_mail_notify_enable() ? "和回复邮件提醒" : ""; ?>)</label>
  </p>
  <p>
    <input type="text" name="<?php echo $arg2; ?>" class="comm_input input_2" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
    <label for="<?php echo $arg2; ?>">网站 (选填)</label>
  </p>
  <?php endif; ?>
  <div>
    <textarea name="<?php echo $arg3; ?>" class="comm_input" tabindex="4" rows="8" cols="40"></textarea>
	<div class="alignRight">
	<?php if(is_comment_mail_notify_enable()) : ?>
	<div class="floatL" style="padding-top:6px;">
		<input type="checkbox" name="email_notify" id="email_notify" checked="checked" tabindex="5"/>
    	<label>有人回复时邮件提醒</label>
	</div>
	<?php endif; ?>
	<input name="submit" type="submit" id="submit" tabindex="5" value="提交评论" />
    <input type="hidden" name="hackerzhou_article_id" id="hackerzhou_article_id" value="<?php echo $id; ?>" />
    <input type='hidden' name='hackerzhou_com_parent_id' id='hackerzhou_com_parent_id' value='0' />
    </div>
  </div>
  <?php do_action('comment_form', $post->ID); ?>
</form>
</div>
</div>
</div>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>
