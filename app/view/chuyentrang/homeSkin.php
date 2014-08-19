<?php
class homeSkin{
	
	function loadDefault($option) {
		global $config, $html;
		
		$html->cssPackage('slider/slider');
		
		$html->js('jquery-1.6.2', 0);
		$html->js('package/slider/jquery.easing.1.3.min',  0);
		$html->js('package/slider/trans-banner.min', 0);
		
		$html->jscript('
				jQuery(function($){
					$("#slider").TransBanner({
						slide_autoplay: true,
						button_show_back: true,
						button_numbers_autohide: false,
						button_numbers_horizontal: true,
		
						caption_bg_color: "#333333",
						caption_bg_opacity: 0.5,
						caption_bg_radius: 0,
						caption_padding_x : 18,
						caption_padding_y : 12,
		
						caption_float_mode: true,
						package_path: "'.$config->vars['cdn'].'/js/frontend/chuyentrang/package/slider"
					});
				});
			', 0);
		
		
		$this->imagehepler = Helper::getHelper('image');
		$this->formathepler = Helper::getHelper('format');
		$this->obj = Helper::getTrait('object');
		
		$BWHTML .= <<<EOF
			<div class='top'>
				<div id='lastest'>
					<div class='header'>
						Bài viết mới nhất
					</div>
					<foreach=" $option['lastest'] as $lastest ">
					<article class='item' style='margin-top: 10px;'>
						<figure>
							<a class='image' href='{$this->obj->url($lastest, 'posts')}' title='{$lastest[$this->alias]['title']}'>
	                           {$this->imagehepler->displayImage($lastest['File'], 70, 45, 1, 1)}
							</a>
						</figure>
						<header>
							<h3>
								<a href='{$this->obj->url($lastest, 'posts')}' title='{$lastest[$this->alias]['title']}'>
	                               {$lastest[$this->alias]['title']}
								</a>
							</h3>
						</header>
						<div class="clear"></div>
					</article>
					</foreach>
				</div>
				<div class="clear"></div>
			</div>
EOF;
		return $BWHTML;
	}

}