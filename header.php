<?php
global $host;
global $options;
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$options = get_option('m2_options');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
<?php if ($options['minify']) : ?>
<link rel="stylesheet" href="<?php bloginfo('template_url');?>/min/b=wp-content/themes/m2/assets&f=reset.css,mangguo.css">
<?php else : ?>
<link rel="stylesheet" href="<?php bloginfo('template_url');?>/assets/reset.css">
<link rel="stylesheet" href="<?php bloginfo('template_url');?>/assets/mangguo.css">
<?php endif; ?>
<link rel="alternate" href="<?php bloginfo('rss2_url'); ?>" type="application/rss+xml">
<?php if (!empty($options['page_width'])) : ?>
<style>
.header, .content, .footer {
	width: <?php echo $options['page_width']; ?>;
}
</style>
<?php endif; ?>
</head>
<body>
<div class="header">
	<h1 class="logo"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
	<a class="pagerank" href="http://pagerank.adminunion.com/<?php echo $host; ?>" title="PageRank 值查询" target="_blank"><img src="http://pagerank.adminunion.com/widget.php?size=xl&domain=<?php echo $host; ?>" alt=""></a>
	<ul class="quick-menu clearfix">
		<li><span>欢迎光临<?php if($_COOKIE['comment_author_' . COOKIEHASH] != '') echo '，<b>' . $_COOKIE['comment_author_'.COOKIEHASH] . '</b>'; ?></span></li>
		<li class="subscribe"><a href="<?php bloginfo('rss2_url'); ?>" target="_blank">订阅</a></li>
		<li class="random"><?php $random = get_posts('numberposts=1&orderby=rand'); foreach($random as $post) : ?><a href="<?php the_permalink(); ?>">随机</a><?php endforeach; ?></li>
	</ul>
</div>
<div class="content">
	<div id="slide" class="slide<?php if (isset($_COOKIE['slide_mini'])) echo ' slide-mini'; ?>">
		<ol class="slide-content">
<?php foreach ($options['slide_links'] as $k => $v) : ?>
			<li style="<?php if ($k == 0) echo 'display:block;'; else echo 'display:none;'; ?>">
				<h3><a target="_blank" href="<?php echo $v[2]; ?>"><?php echo $v[0]; ?></a></h3>
				<p><?php echo $v[1]; ?></p>
				<p><a target="_blank" href="<?php echo $v[2]; ?>">猛击这里查看</a></p>
			</li>
<?php endforeach; ?>
		</ol>
		<ul class="slide-nav clearfix">
<?php foreach ($options['slide_links'] as $k => $v) : ?>
			<li<?php if ($k == 0) echo ' class="active"'; ?>><?php echo $k + 1; ?></li>
<?php endforeach; ?>
		</ul>
		<span class="prev"></span>
		<span class="next"></span>
	</div>
	<div id="slide-toggle" class="slide-toggle clearfix">
<?php if (isset($_COOKIE['slide_mini'])) : ?>
		<span class="toggle toggle-mini">切换到完整模式</span>
<?php else : ?>
		<span class="toggle">切换到精简模式</span>
<?php endif; ?>
	</div>
<?php get_search_form(); ?>
	<div class="tab">
		<ul class="clearfix">
			<li<?php if (is_home()) echo ' class="current-menu-item"'; ?>><a href="<?php bloginfo('url'); ?>">首页</a></li>
<?php wp_nav_menu(array('theme_location' => 'tab', 'container' => false, 'menu_class' => false, 'menu_id' => false, 'items_wrap' => '%3$s')); ?>
		</ul>
	</div>
	<div class="outline">
		<div class="text-links">
<?php foreach ($options['text_links'] as $k => $v) : if ($k != 0) echo '<s>/</s>'; ?><a target="_blank" href="<?php echo $v[2]; ?>" style="color:<?php echo $v[1]; ?>;"><?php echo $v[0]; ?></a><?php endforeach; ?>
		</div>
		<div class="clearfix">
			<ul class="category-list clearfix">
<?php $args = array('orderby' => 'count', 'order' => 'DESC', 'hide_empty' => false, 'title_li' => ''); wp_list_categories($args); ?>
			</ul>
			<div class="counter">标签数目统计值 <a href="/all/"><?php $counter = wp_count_terms('post_tag'); $terms = str_split($counter); foreach ($terms as $term) echo '<b>' . $term . '</b>'; ?></a></div>
		</div>
	</div>