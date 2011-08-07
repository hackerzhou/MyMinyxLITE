<div id="sidebar">
  <?php 
  	$about_info = get_personal_info();
  	if (strlen($about_info) > 0) :?>
  <div id="about">
    <div>
      <h2>
        <?php _e('About','minyx2Lite')?>
      </h2>
      <div class="info"></div>
    </div>
  </div>
  <?php endif; ?>
  <form method="get" id="searchform" action="<?php echo home_url(); ?>/">
    <div class="searchfromInnerDiv">
      <div id="searchsubmitBtn"></div>
      <div class="icon"></div>
      <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
    </div>
  </form>
  <div id="recent"> <a href="<?php bloginfo('rss2_url'); ?>" class="mini_rss">
    <?php _e('RSS2.0 Entries','minyx2Lite')?>
    </a>
    <h2>最新文章</h2>
    <ul>
      <?php wp_get_archives('type=postbypost&limit=10&format=html'); ?>
    </ul>
  </div>
  <div class="sideR">
    <ul>
    	<?php wp_list_categories('show_count=1&title_li=<h2>分类目录</h2>&hierarchical=true'); ?>
      <li class="archives">
        <h2>文章存档</h2>
        <ul>
          <?php wp_get_archives('type=monthly&limit=12&show_post_count=true'); ?>
          <?php
          	$oldArchives=wp_get_old_archives(12);
          	if($oldArchives!=''){
          		echo"<li><a href=\"#\" onclick=\"document.getElementById('more_archives').style.display='block';this.parentNode.style.display='none';return false;\">更多...</a></li>";
          	}
          ?>
        </ul>
        <ul id="more_archives" style="display:none;">
        	<?php echo $oldArchives; ?>
        </ul>
      </li>
    </ul>
  </div>
  <div class="sideL">
    <ul>
    	<?php wp_list_bookmarks(); ?>
    </ul>
  </div>
</div>
