<?php
/*
Template Name: 友情链接
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
<link rel="alternate" href="<?php bloginfo('rss2_url'); ?>" type="application/rss+xml">
<style>
body {
	font-family: 'Lucida Grande', Helvetica, Arial, sans-serif;
	font-size: 100%;
	line-height: 1.5;
	margin: 0;
	padding: 0em 4em;
	margin: 0;
}
h1, h2, h3, h4, h5, h6 {
	margin-top: 1.5em;
	margin-bottom: 0.5em;
}
h1 {
	border-bottom: 3px solid #3975CE;
	font-size: 2.0em;
	color: #000;
	line-height: 1.5em;
}
h2 {
	font-size: 1.6em;
	color: #557;
}
h3 {
	font-size: 1.3em;
	color: #557;
}
h4 {
	font-size: 1.2em;
	color: #224;
}
h5 {
	font-size: 1.1em;
	color: #224;
}
h6 {
	font-size: 1.0em;
	color: #224;
}
h1 a {
	border-bottom: none;
}
a {
	border-bottom: 1px dotted #224;
	color: #3975CE;
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
p, pre, blockquote, table, ul, ol, dl {
	margin-top: 1em;
	margin-bottom: 1em;
}
ul ul, ul ol, ol ol, ol ul {
	margin-top: 0.5em;
	margin-bottom: 0.5em;
}
ul {
	margin-left: 2em;
	padding-left: 0.5em;
}
li {
	margin: 0.3em auto;
}
dt {
	font-weight: bold;
	margin: .5em 0;
}
dd {
	text-indent: 1em;
}
p {
	text-indent: 2em;
}
img {
	border: none;
}
pre {
	padding: 8px;
	border: 1px solid #C7CFD5;
	border-left-width: 5px;
	color: #666;
	font-family: Courier, Consolas, monospace;
	background-color: #F5F9FD;
	overflow: auto;
	white-space: pre-wrap;
}
code {
	padding: 2px .5em;
	border-bottom: 1px solid #C7CFD5;
	background-color: #F5F9FD;
}
blockquote {
	color: #666;
	font-style: italic;
	margin-left: 2em;
	padding: 8px 8px 8px 35px;
	overflow: auto;
}
table {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0;
}
th, td {
	border: 1px solid #ccc;
	padding: 0.3em;
}
th {
	background-color: #F0F0F0;
}
hr {
	border: none;
	border-top: 1px solid #CCC;
	width: 100%;
}
del {
	text-decoration: line-through;
	color: #777;
}
#footer {
	margin: 50px 0 40px;
	padding-top: 10px;
	border-top: 1px solid #E3E3E3;
	text-align: left;
	font-size: 90%;
}
.current_page_item {
	font-weight: bold;
}
</style>
</head>
<body>
<div id="page">
	<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h2><?php the_title(); ?></h2>
	<p><?php the_content(); ?></p>
<?php endwhile; endif; ?>
	<ol>
<?php wp_list_bookmarks('title_li=&categorize=0&show_name=1&show_description=1&orderby=rand&between= - '); ?>
	</ol>
	<h3>加入链接</h3>
	<ul>
		<li>网站名称：<?php bloginfo('name'); ?></li>
		<li>网站地址：<?php bloginfo('url'); ?></li>
		<li>网站描述：<?php bloginfo('description'); ?></li>
	</ul>
	<h2>其他页面</h2>
	<ul>
<?php wp_list_pages('title_li='); ?>
	</ul>
	<p id="footer">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
</div>
<?php wp_footer(); ?>
</body>
</html>