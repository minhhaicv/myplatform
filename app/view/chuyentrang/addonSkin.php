<?php

class addonSkin {

	
	function displayRandom($option = array()){
		global $config;
		$this->format = Helper::getTrait('object');
		$BWHTML = <<<EOF
			<div id='hottest'>
        		<div class='header'>Bài viết may mắn</div>
            		<foreach=" $option as $item">
            		<a href='{$this->format->url($item)}' title='{$item['Post']['title']}'>
                		<span class='icon'></span>
                		  {$item['Post']['title']}
                		</a>
            		</foreach>
    		</div>
EOF;
		
	}
	
	function displayTag($option = array()){
		global $config;
		$this->format = Helper::getTrait('object');
		
		$BWHTML = <<<EOF
			<div class='sidebar-item'>
				<div class='header'>
					<a href='{$config->base_url}tag/' title='{$pLang->getWords('global_portlet_tag','Tag nổi bật')}'>
						Tag nổi bật
					</a>
				</div>
				<div class='content'>
					<foreach=" $option as $tag ">
						<a class='tag-item' href='{$this->format->getUrl($tag, 'tag')}'' title="{$tag->getTitle()}">
							{$tag->getTitle()}
						</a>
					</foreach>
					<div class='clear'></div>
				</div>
			</div>
EOF;
	}

	function displayAds(){
		global $config;
	
		$BWHTML = <<<EOF
			<div class='sidebar-item-no-border'>
				<a href='http://www.myphamthanhthuy.vn/' class='ad-item' title='Mỹ phẩm thanh thỷ' target='_blank'>
					<img src='{$config->vars['upload']}/quang-cao/my-pham-thanh-thuy.jpg' width='300' />
				</a>
			</div>
EOF;
	}

	function displayFacebook(){
		global $config;
	
		$BWHTML = <<<EOF
			<div class='sidebar-item' style='border-bottom: 0px; margin-bottom: 0;'>
				<div class='header'>
					<a href='{$config->base_url}tag/' title='Hãy like fanpage của chúng tôi nếu bạn thấy thích'>
						Fanpage của chúng tôi
					</a>
				</div>
			</div>
			<div id='fb-root'></div>
			<div class="fb-like-box" data-href="http://www.facebook.com/chuyentrangphunu" data-width="298" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false"></div>					
								
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			</script>
EOF;
	}
}