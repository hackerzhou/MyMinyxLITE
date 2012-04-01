<?php
the_page_title(); 
$cse_id = get_cse_id_if_enable();
if (is_search() && $cse_id !== false) { ?>
	<div id="cse" style="width: 100%">Loading ...</div>
	<script src="http://ajax.googleapis.com/jsapi" type="text/javascript"></script>
	<script type="text/javascript">
	  var searchStr='<?php echo strip_tags($_POST["s"]); ?>';
	  google.load('search', '1', {language : 'zh-CN',"nocss" : true});
	  google.setOnLoadCallback(function() {
		var myCb = function(){scrollToElement("#container");setHoverCursor('.gsc-cursor-page', 'pointer');};
		var customSearchControl = new google.search.CustomSearchControl('<?php echo $cse_id; ?>');
		customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
		customSearchControl.setSearchCompleteCallback(this, myCb);	  
		customSearchControl.draw('cse');
		$('.gsc-input').val(searchStr);
		$('.gsc-search-button').click();
	  }, true);
	</script>
<?php
} else if(have_posts()) {
	while (have_posts()) {
		the_post();
	?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="阅读文章：<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<?php if(!is_page()) { ?>
				<small><?php the_time('Y-m-d G:i') ?> by <?php the_author(); edit_post_link('编辑', ' - ', '  '); ?></small>
			<?php } ?>
			<div class="entry"><?php 
				if (is_single() || is_page()) {
					the_content();
				} else {
					my_abstract(); 
				}
				?>
			</div>
			<?php if(!is_page()) { ?>
			<ul class="postmetadata alt">
			  <?php if(!is_single()) { ?>
				<li class="icon_comment icon_r"><?php comments_popup_link('暂无评论','1 条评论','% 条评论'); ?></li>
			  <?php } ?>
			  <li class="icon_cat"><div class="icon"></div><strong>分类：</strong><?php the_category(', ') ?></li>
			  <li class="icon_bullet"><div class="icon"></div><strong>标签：</strong><?php the_tags('',', ','') ?></li>
			</ul>
			<?php } ?>
		</div>
	<?php
	}
	the_license_information(); 
	if(is_single()) {
		comments_template(); 
	}
	if(!is_single() && !is_page()) { 
	?>
		<div class="navigation"><?php native_pagenavi(); ?></div>
	<?php
	}
}
?>