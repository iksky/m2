<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
<link href="/feed/" rel="alternate" type="application/rss+xml" />
</head>
<body>
<div class="header">
	<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> - <?php bloginfo('description'); ?></h1>
	<p><a href="<?php bloginfo('url'); ?>">首页</a>
<?php $pages = get_pages(); foreach ($pages as $page) : ?>
 / <a href="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></a>
<?php endforeach; ?>
	</p>
</div>
<hr />
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="center">
	<h2><?php the_title(); ?></h2>
<?php the_content(); ?>
<?php if (is_page('all')) : ?>
	<p><?php wp_tag_cloud('smallest=8&largest=22&number=0'); ?></p>
<?php endif; ?>
<?php if (is_page('blogroll')) : ?>
	<ol>
<?php wp_list_bookmarks('title_li=&categorize=0&show_name=1&show_description=1&orderby=rand&between= - '); ?>
	</ol>
	<h3>加入链接</h3>
	<ul>
		<li>网站名称：<?php bloginfo('name'); ?></li>
		<li>网站地址：<?php bloginfo('url'); ?></li>
		<li>网站描述：<?php bloginfo('description'); ?></li>
	</ul>
<?php endif; ?>
</div>
<?php endwhile; else : endif; ?>
<hr />
<div class="footer">
	<address>&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></address>
</div>
<?php wp_footer(); ?>
</body>
</html>