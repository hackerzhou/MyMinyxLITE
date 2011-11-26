<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>

<div id="content" class="widecolumn">
<h2 class="pagetitle"><?php _e('Browse the blog archives','minyx2Lite')?></h2>
  
    <h2><?php _e('Archives by Month','minyx2Lite')?>:</h2>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>
  <h2><?php _e('Archives by Subject','minyx2Lite')?>:</h2>
  <ul>
    <?php wp_list_categories(); ?>
  </ul>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
