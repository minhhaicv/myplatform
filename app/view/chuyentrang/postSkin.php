<?php
class postSkin{
	
	function detail($item, $option = array()){
		global $config;
		
		$this->obj = Helper::getTrait('object');
		
		$BWHTML .= <<<EOF
				<div id="fb-root"></div>
				<script type='text/javascript'>
					(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<section class="main-content {$item['Category']['slug']}">
					<article id='article-detail'>
						<header class="header">
							<h1>{$item[$this->alias]['title']}</h1>
						</header>
						<div class='content'>
							{$this->obj->decode($item[$this->alias]['content'])}
							<div class='clear'></div>
						</div>
						
						<footer>
							
						</footer>
					</article>
				</section>
EOF;
		return $BWHTML;
	}
	
	function category($option = array()){
		global $config;
		
		$this->formathepler = Helper::getHelper('format');
		$this->imagehepler = Helper::getHelper('image');
		$this->format = Helper::getTrait('object');
		
		$BWHTML .= <<<EOF
				    <section class="main-content {$option['category']['Category']['slug']}">
						<header class='circle'>
							<h2 class="category-header">{$option['category']['Category']['title']}</h2>
							<figure class='image-wrap {$option['category']['Category']['slug']}'>
								&nbsp;
							</figure>
						</header>
						<article class='circle-content'>
							<header class="lastest-article-header">
								<h3>
									<a href='{$this->format->url($option['first'], "posts")}' title='{$option['first'][$this->alias]['title']}'>
										{$option['first'][$this->alias]['title']}	
									</a>
								</h3>
							</header>
							<div class='article-content' >
								{$this->formathepler->cutString($this->format->decode($option['first'][$this->alias]['content']), 250)}
							</div>
							<div class="clear"></div>
						</article>
						
						<div class='category-article'>
							<foreach=" $option['list'] as $item ">
							<article class='article-item'>
								<figure class="left">
									<a class='article-image' href="{$this->format->url($item, "posts")}">
										{$this->imagehepler->displayImage($item['File'], 150, 100, 1, 1)}
									</a>
								</figure>
								<header class="article-header">
									<a href='{$this->format->url($item, "posts")}' title='{$item[$this->alias]['title']}'>
									   {$item[$this->alias]['title']}
									</a>
								</header>
								<div class='article-content' >
									 {$this->formathepler->cutString($this->format->decode($item[$this->alias]['content']), 250)} 
								</div>
								<div class="clear"></div>
							</article>
							</foreach>
						</div>
						<div class='clear'></div>
				</section>
EOF;
		return $BWHTML;
	}
}