<?php

/**
 * 使主题支持自定义菜单
 */
if (function_exists('register_nav_menus')) {

	register_nav_menus(array(
		'tab' => '导航菜单',
		'sitemap' => '页尾菜单'
	));

}

/**
 * 使主题支持小工具
 */
if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<div class="hd">',
		'after_title' => '</div><div class="bd">'
	));

}

/**
 * 不用插件实现翻页功能
 */
function get_pagenavi () {

	global $wp_query, $wp_rewrite;

	$wp_query -> query_vars['paged'] > 1 ? $current = $wp_query -> query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg('page', '%#%'),
		'format' => '',
		'total' => $wp_query -> max_num_pages,
		'current' => $current,
		'mid_size' => 5,
		'prev_text' => '«',
		'next_text' => '»'
	);

	if ($wp_rewrite -> using_permalinks()) {
		$pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
	}

	if (!empty($wp_query -> query_vars['s'])) {
		$pagination['add_args'] = array('s' => get_query_var('s'));
	}

	echo paginate_links($pagination);

}

/**
 * 不用插件实现阅读计数功能（计数）
 */
function get_post_views ($post_id) {

	$count_key = 'views';
	$count = get_post_meta($post_id, $count_key, true);

	if ($count == '') {
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
		$count = '0';
	}

	echo number_format_i18n($count);

}

/**
 * 不用插件实现阅读计数功能（读数）
 */
function set_post_views () {

	global $post;

	$post_id = $post -> ID;
	$count_key = 'views';
	$count = get_post_meta($post_id, $count_key, true);

	if (is_single() || is_page()) {

		if ($count == '') {
			delete_post_meta($post_id, $count_key);
			add_post_meta($post_id, $count_key, '0');
		} else {
			update_post_meta($post_id, $count_key, $count + 1);
		}

	}

}

add_action('get_header', 'set_post_views');

/**
 * 过滤文章搜索类型
 */
function search_query_filter ($query) {

	if ($query -> is_search) {
		$query -> set('post_type', 'post');
	}
	return $query;

}

add_filter('pre_get_posts', 'search_query_filter');

/**
 * 评论邮件通知功能
 */
function comment_mail_notify ($comment_id) {

	$comment = get_comment($comment_id);
	$parent_id = $comment -> comment_parent ? $comment -> comment_parent : '';
	$spam_confirmed = $comment -> comment_approved;

	if (($parent_id != '') && ($spam_confirmed != 'spam')) {

		$wp_email = 'webmaster@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$to = trim(get_comment($parent_id) -> comment_author_email);

		$subject = '你在 [' . get_option("blogname") . '] 的留言有了回应';
		$message = '
	<div style="background-color:#EEF2FA;border:1px solid #D8E3E8;color:#111;padding:0 15px;border-radius:5px;">
		<p>' . trim(get_comment($parent_id) -> comment_author) . ', 你好!</p>
		<p>你曾在《' . get_the_title($comment -> comment_post_ID) . '》的留言:<br>' . trim(get_comment($parent_id) -> comment_content) . '</p>
		<p>' . trim($comment -> comment_author) . ' 给你的回应:<br />' . trim($comment -> comment_content) . '<br></p>
		<p>你可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整内容</a></p>
		<p><strong>感谢你对 <a href="' . get_option('home') . '" target="_blank">' . get_option('blogname') . '</a> 的关注，欢迎<a href="' . get_option('home') . '/feed/" target="_blank">订阅本站</a></strong></p>
		<p><strong>您可以直接回复此邮件与我联系～</strong></p>
	</div>';

		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

		wp_mail($to, $subject, $message, $headers);

	}

}

add_action('comment_post', 'comment_mail_notify');

/**
 * 为主题添加管理选项
 * @class Options
 */
class Options {

	/**
	 * 获取选项组
	 */
	function get_options () {

		//在数据库中获取选项组
		$options = get_option('m2_options');

		//如果数据库中不存在该选项组, 设定这些选项的默认值, 并将它们插入数据库
		if (!is_array($options)) {
			$options['logo_img'] = '';
			$options['page_width'] = '';
			$options['pagerank'] = false;
			$options['minify'] = false;
			$options['slide_links'] = array(
				array('美国 BlueHost 主机', '优质国外主机，每月只需 $5.95，支持简体中文。', 'http://www.bluehost.com/track/mangguo'),
				array('美国 HostMonster 主机', '与 Bluehost 同属一家公司，每月只需 $6.95，支持简体中文。', 'http://www.hostmonster.com/track/mangguo'),
				array('美国 JustHost 主机', '超级便宜的虚拟主机，每月低至 $3.95，WordPress 博客首选。', 'http://stats.justhost.com/track?c38717e2731a3cc908b64aadd428b8aba')
			);
			$options['text_links'] = array(
				array('美国 BlueHost 主机', '优质国外主机，每月只需 $5.95，支持简体中文。', 'http://www.bluehost.com/track/mangguo'),
				array('美国 HostMonster 主机', '与 Bluehost 同属一家公司，每月只需 $6.95，支持简体中文。', 'http://www.hostmonster.com/track/mangguo'),
				array('美国 JustHost 主机', '超级便宜的虚拟主机，每月低至 $3.95，WordPress 博客首选。', 'http://stats.justhost.com/track?c38717e2731a3cc908b64aadd428b8aba'),
				array('芒果主机，专业的美国主机导购信息', 'red', 'http://www.mangguo.de/?source=mangguo')
			);
			$options['article_a1'] = array(
				'active' => true,
				'content' => '<a href="http://www.bluehost.com/track/mangguo/CODE3" target="_blank"><img src="http://img04.taobaocdn.com/tps/i4/T1cPnOXk4aXXX0o.fS-300-250.gif" width="300" height="250" alt=""></a>'
			);
			$options['article_a2'] = array(
				'active' => true,
				'content' => '<a href="http://www.bluehost.com/track/mangguo/CODE5" target="_blank"><img src="http://img03.taobaocdn.com/tps/i3/T1zdHJXhBrXXX0o.fS-300-250.gif" width="300" height="250" alt=""></a>'
			);
			$options['sidebar_a3'] = array(
				'home' => false,
				'single' => true,
				'content' => '<script>
google_ad_client = "ca-pub-9763316970959340";
google_ad_slot = "4165466641";
google_ad_width = 250;
google_ad_height = 250;
</script>
<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
			);
			update_option('m2_options', $options);
		}

		//返回选项组
		return $options;
	}

	/**
	 * 初始化选项
	 */
	function set_options () {

		//如果是post提交数据, 对数据进行限制, 并更新到数据库
		if(isset($_POST['options_save'])) {
			$options = array(
				'logo_img' => $_POST['logo_img'],
				'page_width' => $_POST['page_width'],
				'pagerank' => $_POST['pagerank'],
				'minify' => $_POST['minify'],
				'slide_links' => array(),
				'text_links' => array(),
				'article_a1' => array(
					'active' => isset($_POST['a1_active']),
					'content' =>  stripslashes($_POST['a1_content']),
				),
				'article_a2' => array(
					'active' => isset($_POST['a2_active']),
					'content' =>  stripslashes($_POST['a2_content']),
				),
				'sidebar_a3' => array(
					'home' => isset($_POST['a3_home']),
					'single' => isset($_POST['a3_single']),
					'content' =>  stripslashes($_POST['a3_content']),
				)
			);
			for ($i = 0; $i < sizeof($_POST['slide_name']); $i++) {
				array_push($options['slide_links'], array(
					$_POST['slide_name'][$i],
					$_POST['slide_desc'][$i],
					$_POST['slide_url'][$i]
				));
			}
			for ($i = 0; $i < sizeof($_POST['text_name']); $i++) {
				array_push($options['text_links'], array(
					$_POST['text_name'][$i],
					$_POST['text_desc'][$i],
					$_POST['text_url'][$i]
				));
			}
			update_option('m2_options', $options);
		}

		//否则，重新获取选项组，也就是对数据进行初始化
		else {
			Options::get_options();
		}

		add_menu_page('主题选项', '主题选项', 'edit_themes', basename(__FILE__), array('Options', 'display'));

	}

	/**
	 * 选项设置页
	 */
	function display () {

		$options = Options::get_options(); ?>

<div class="wrap">
	<h2>主题选项（M2）<a class="add-new-h2" href="http://www.mangguo.org/" target="_blank">芒果小站</a></h2>
	<div class="metabox-holder has-right-sidebar">
		<div class="inner-sidebar">
			<div class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<h3 class="hndle"><span>美国主机推荐</span></h3>
					<div class="inside">
			<table class="hosting" style="width:100%;">
				<tr id="justhost">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://stats.justhost.com/track?c38717e2731a3cc908b64aadd428b8aba"><img alt="JustHost" src="http://img02.taobaocdn.com/tps/i2/T1vWfwXaXcXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="hostgator">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://secure.hostgator.com/cgi-bin/affiliates/clickthru.cgi?id=mangguo"><img alt="HostGator" src="http://img02.taobaocdn.com/tps/i2/T1fYvvXgdnXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="bluehost">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.bluehost.com/track/mangguo"><img alt="BlueHost" src="http://img04.taobaocdn.com/tps/i4/T1qFYwXi8cXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="hostmonster">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.hostmonster.com/track/mangguo"><img alt="HostMonster" src="http://img03.taobaocdn.com/tps/i3/T1dVYvXolnXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="lunarpages">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.lunarpages.com/id/mangguo"><img alt="LunarPages" src="http://img03.taobaocdn.com/tps/i3/T1wXrwXjJbXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="ixwebhosting">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.ixwebhosting.com/templates/ix/v2/affiliate/clickthru.cgi?id=mangguo"><img alt="IXWebHosting" src="http://img01.taobaocdn.com/tps/i1/T1zx6tXldlXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="site5">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.site5.com/in.php?id=85885"><img alt="Site5" src="http://img03.taobaocdn.com/tps/i3/T1zlntXeBoXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="dreamhost">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://www.dreamhost.com/r.cgi?674076"><img alt="DreamHost" src="http://img04.taobaocdn.com/tps/i4/T1rRvuXahfXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="hawkhost">
					<td class="web-hosting" align="center">
						<a target="_blank" href="https://my.hawkhost.com/aff.php?aff=3100"><img alt="HawkHost" src="http://img02.taobaocdn.com/tps/i2/T1Yj6vXodgXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
				<tr id="godaddy">
					<td class="web-hosting" align="center">
						<a target="_blank" href="http://affiliate.godaddy.com/redirect/95B163F64AA934DC393CB79E3EF3602108CE0DCCF913CA81DECFD2130290BC91/?r=mangguo"><img alt="Godaddy" src="http://img01.taobaocdn.com/tps/i1/T1pnTvXaFdXXcmPIvc-150-35.png"></a>
					</td>
				</tr>
			</table>
					</div>
				</div>
			</div>
		</div>
		<form method="post" name="options_form">
		<div class="has-sidebar">
			<div class="has-sidebar-content" id="post-body-content">
				<div class="meta-box-sortabless">
					<div class="postbox">
						<h3 class="hndle"><span>基本设置</span></h3>
						<div class="inside">
							<ul>
								<li>LOGO图片：<input type="text" value="<?php echo $options['logo_img']; ?>" class="regular-text" name="logo_img">（大小：130x45 像素）</li>
								<li>页面宽度：<input type="text" name="page_width" class="small-text" value="<?php echo $options['page_width']; ?>">（示例：950px 或 95%，默认为 960px）</li>
								<li>
									PR值挂件：
									<label style="vertical-align:inherit;"><input type="checkbox" name="pagerank"<?php if ($options['pagerank']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（使用第三方挂件显示 Google PageRank 值）
								</li>
								<li>
									资源合并：
									<label style="vertical-align:inherit;"><input type="checkbox" name="minify"<?php if ($options['minify']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（使用 <a href="http://www.mangguo.org/minify-merge-compress-javascript-and-css-file/" target="_blank">minify</a> 方案合并加载多个 css 和 js 文件，有效提升访问速度，<span style="color:red">强烈建议开启</span>）
								</li>
							</ul>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>轮播广告设置</span></h3>
						<div class="inside link-box">
							<table width="100%" cellspacing="3" cellpadding="3">
								<tr>
									<th scope="col">标题</th>
									<th scope="col">描述</th>
									<th scope="col">链接</th>
									<th scope="col">操作</th>
								</tr>
								<?php foreach ($options['slide_links'] as $v) : ?>
								<tr class="alternate">
									<td><input type="text" name="slide_name[]" value="<?php echo $v[0]; ?>" style="width:100%;"></td>
									<td><input type="text" name="slide_desc[]" value="<?php echo $v[1]; ?>" style="width:100%;"></td>
									<td><input type="text" name="slide_url[]" value="<?php echo $v[2]; ?>" style="width:100%;"></td>
									<td style="text-align:center;"><a class="del-link" href="javascript:;">删除</a></td>
								</tr>
								<?php endforeach; ?>
							</table>
							<a class="add-link" href="javascript:;" data-type="slide">增加一个新的链接</a>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文字广告设置</span></h3>
						<div class="inside link-box">
							<table width="100%" cellspacing="3" cellpadding="3">
								<tr>
									<th scope="col">标题</th>
									<th scope="col">颜色（示例：#FF0000）</th>
									<th scope="col">链接</th>
									<th scope="col">操作</th>
								</tr>
								<?php foreach ($options['text_links'] as $v) : ?>
								<tr class="alternate">
									<td><input type="text" name="text_name[]" value="<?php echo $v[0]; ?>" style="width:100%;"></td>
									<td><input type="text" name="text_desc[]" value="<?php echo $v[1]; ?>" style="width:100%;"></td>
									<td><input type="text" name="text_url[]" value="<?php echo $v[2]; ?>" style="width:100%;"></td>
									<td style="text-align:center;"><a class="del-link" href="javascript:;">删除</a></td>
								</tr>
								<?php endforeach; ?>
							</table>
							<a class="add-link" href="javascript:;" data-type="text">增加一个新的链接</a>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文章前置广告设置</span></h3>
						<div class="inside">
							<ul>
								<li>
									是否启用：
									<label style="vertical-align:inherit;"><input type="checkbox" name="a1_active"<?php if ($options['article_a1']['active']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（大小：300x250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;" name="a1_content"><?php echo $options['article_a1']['content']; ?></textarea>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文章后置广告设置</span></h3>
						<div class="inside">
							<ul>
								<li>
									是否启用：
									<label style="vertical-align:inherit;"><input type="checkbox" name="a2_active"<?php if ($options['article_a2']['active']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（大小：300x250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;" name="a2_content"><?php echo $options['article_a2']['content']; ?></textarea>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>侧栏广告设置</span></h3>
						<div class="inside">
							<ul>
								<li>
									显示范围：
									<label style="vertical-align:inherit;"><input type="checkbox" name="a3_home"<?php if ($options['sidebar_a3']['home']) echo ' checked="checked"'; ?>>&nbsp;首页</label>
									<label style="vertical-align:inherit;"><input type="checkbox" name="a3_single"<?php if ($options['sidebar_a3']['single']) echo ' checked="checked"'; ?>>&nbsp;内页</label>
									（宽度：250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;" name="a3_content"><?php echo $options['sidebar_a3']['content']; ?></textarea>
						</div>
					</div>
				</div>
				<div>
					<p class="submit">
						<input type="submit" name="options_save" class="button-primary" value="更新设置">
					</p>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
<script>
(function($){

	//添加链接
	$('.link-box').delegate('.add-link', 'click', function (e) {

		var type = $(e.currentTarget).attr('data-type'),
			template = '<tr class="alternate">' +
			'	<td><input type="text" name="' + type + '_name[]" style="width:100%;"></td>' +
			'	<td><input type="text" name="' + type + '_desc[]" style="width:100%;"></td>' +
			'	<td><input type="text" name="' + type + '_url[]" style="width:100%;"></td>' +
			'	<td style="text-align:center;"><a class="del-link" href="javascript:;">删除</a></td>' +
			'</tr>';

		$(template).insertAfter($(e.delegateTarget).find('tr.alternate:last'));

	});

	//删除链接
	$('.link-box').delegate('.del-link', 'click', function (e) {

		if ($(e.delegateTarget).find('tr.alternate').length != 1) {
			$(this).parent('td').parent('tr').remove();
		}

	});

})(jQuery);
</script>

<?php }

}

//注册初始化方法
add_action('admin_menu', array('Options', 'set_options'));

?>