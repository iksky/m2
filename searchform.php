	<div class="search">
		<div class="message"><b>我说</b>：<?php bloginfo('description'); ?></div>
		<form action="/" method="get" class="searchform clearfix">
			<span class="s"><input type="text" name="s" id="s" value="<?php the_search_query(); ?>"></span>
			<span class="searchsubmit"><button type="submit"></button></span>
		</form>
	</div>