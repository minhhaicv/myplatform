<?php
class globalSkin {
		
	function layout() {
		global $config, $view, $html;
		
		
		$html->css('global');
		$html->css('default');
		
		$html->jscript('
				  var _gaq = _gaq || [];
				  _gaq.push(["_setAccount", "UA-19555017-3"]);
				  _gaq.push(["_setDomainName", ".ladysg.com"]);
				  _gaq.push(["_trackPageview"]);
		
				  (function() {
				    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
				    ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
				    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
				  })();
		
				  (function() {
				    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
				    po.src = "https://apis.google.com/js/plusone.js";
				    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
				  })();
	    	');
		 
		$html->jscript("
		        document.write(\"<!--[if lt IE 9]><script type='text/javascript' src='{$config->vars['cdn']}/js/html5.js'><\/script><![endif]-->\");
		        ", 0);
		
		
		$year = date('Y');
		$BWHTML = <<<EOF
	<div class='wrapper'>
		<header>
			<nav class="top-navbar">&nbsp;</nav>
			
			<div id='banner'>
				<h4 class='branch'>
					<a href='{$config->base_url}' title='Chuyên trang phụ nữ' >
						<img src='{$config->vars['img']}/branch.png' alt='Chuyên trang phụ nữ' />
					</a>
				</h4>
				<aside id='banner-search'>
					<form id='search-bar' action='{$config->base_url}tim-kiem' method='get' class='search-bar'>
						<input type="text" class="span5 search-key" id='search-key' name='keyword' placeholder='Tìm kiếm trong website'/>
						<input type='submit' id='search-button' value='' class='search-button'/>
		    		</form>
				</aside>
			</div>
		</header>

		<div class='main-wrapper'>
			<section class='content-wrapper'>
				{$this->main_content}
				
				<aside class='sidebar'>
				<!--
					<div class='sidebar-item-no-border'>
						<img src='{$config->vars['upload']}/quang-cao/chuyen-trang-phu-nu.jpg' width='300' />
					</div>
					-->
				</aside>
				<div class='clear'></div>
			</section>
		</div>
		
	</div>
	<footer>
		<div id='portlet-list'>
			<div class='footer-wrapper'>
				{$this->portlet_random}
				
				<div id='social-network'>
					<div class='header'>Mạng xã hội</div>				
					<a href='http://www.facebook.com/chuyentrangphunu' rel='no-follow'>
						<span class='icon facebook'></span>
						Chuyên trang trên Facebook
					</a>
						
					<div class='clear'></div>
					<a href='#' rel='no-follow'>
						<span class='icon twitter'></span>
						Chuyên trang trên Twitter
					</a>
					<div class='clear'></div>
						
					<a href='#' rel='no-follow'>
						<span class='icon google-plus'></span>
						Chuyên trang trên Google plus
					</a>
					<div class='clear'></div>
				</div>
				
				<div id='contact-us'>
					<div class='header'>Về chúng tôi</div>				
					<a href='#' rel='no-follow'>
						<span class='icon'></span>
						Feedback
					</a>
					<div class='clear'></div>
					<a href='#' rel='no-follow'>
						<span class='icon'></span>
						Liên hệ quảng cáo
					</a>
					<div class='clear'></div>
					<a href='#' rel='no-follow'>
						<span class='icon'></span>
						Cấu trúc website
					</a>
					<div class='clear'></div>
				</div>
			</div>
		</div>
		<div id='copyright'>
			<div class='footer-wrapper'>
				Copyright © {$year} Chuyên trang dành cho phụ nữ. All rights reserved.
			</div>
		</div>
	</footer>
EOF;
  		return $BWHTML;
	}
}