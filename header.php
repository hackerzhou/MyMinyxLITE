<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php seo_title(); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php the_recource_url('/favicon.ico')?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
<?php wp_head(); ?>
<script type="text/javascript" src="<?php echo get_jquery(); ?>"></script>
<script type="text/javascript">
	function scrollToElement(ele){$body=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');var targetY=$(ele).offset().top;var current=$body.scrollTop();var pgHeight=getPageHeight();var direction=targetY>current?1:-1;if(Math.abs(current-targetY)>pgHeight){current=targetY-direction*pgHeight;$body.scrollTop(current);}$body.animate({scrollTop:targetY}, 1000);}
	function getPageHeight(){if($.browser.msie){return document.compatMode=="CSS1Compat"?document.documentElement.clientHeight:document.body.clientHeight;}else{return self.innerHeight;}} 
	function setHoverCursor(ele,cursor){$(ele).hover(function(){$(this).css('cursor',cursor);},function(){$(this).css('cursor','auto');});}
	function postComments(){scrollToElement("#respond");if($("input.input_0").length>0){var hasName=$("input.input_0").val().length>0;var hasEmail=$("input.input_1").val().length>0;if(!hasName){$("input.input_0").focus();}else if(!hasEmail){$("input.input_1").focus();}else{$("textarea.comm_input").focus();}}else{$("textarea.comm_input").focus();}}
	function loadJS(js_url){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=js_url;var se=document.getElementsByTagName('script')[0]; se.parentNode.insertBefore(s,se);}
	$(document).ready(function($){var cur=document.createElement("center");cur.appendChild(document.createElement("div"));$("ul#menu li#current").prepend(cur);$("ul#menu li.current_page_item").prepend(cur);var bqe=document.createElement("div");bqe.setAttribute("class","bqe");var b=$("div.entry blockquote");b.append(bqe);var bqs=document.createElement("div");bqs.setAttribute("class","bqs");b.prepend(bqs);$("div.entry blockquote .bqe").css("left",$("div.entry blockquote p:last").width()+35);setHoverCursor("#searchsubmitBtn", "pointer");$("#searchsubmitBtn").click(function(e){$("#searchform").submit()});});
</script>
<?php if (is_need_lazy_load_effect()) { ?>
<script type="text/javascript" src="<?php the_recource_url('/jquery.lazyload.mini.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function($){if(navigator.platform == "iPad"){return;} $("img").not(".cycle img").lazyload({effect:"fadeIn",placeholder: "<?php the_recource_url('/pix/grey.gif') ?>"});});
</script>
<?php } ?>
<?php if (is_singular()) { ?>
<script type="text/javascript">loadJS("<?php the_recource_url('/comments-ajax.js') ?>");</script>
<?php } ?>
<?php if (is_makeitup_comment_enable()) { ?>
<link rel="stylesheet" type="text/css" href="<?php the_recource_url('/markitup/style.css') ?>" />
<script type="text/javascript">loadJS("<?php the_recource_url('/markitup/jquery.markitup.js') ?>");</script>
<?php } ?>
<?php the_google_analytics_if_enable(); ?>
</head>
<body>
<div id="container">
<ul id="topMnu">
  <?php wp_register(); ?>
  <li>
    <?php wp_loginout(); ?>
  </li>
</ul>
<div id="header">
  <ul id="menu">
    <li <?php if(is_home()) { echo 'id="current"'; }?>><a href="<?php echo home_url(); ?>/"> <?php _e('主页','minyx2Lite')?> </a></li>
    <?php wp_list_pages('sort_column=menu_order&title_li=')?>
    <li id="rss"><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries (RSS)','minyx2Lite')?></a></li>
  </ul>
  <h1><a href="<?php echo home_url() ?>/">
    <?php bloginfo('name'); ?>
    </a> <small>
    <?php bloginfo('description'); ?>
    </small></h1>
</div>
<div id="wrapper">
