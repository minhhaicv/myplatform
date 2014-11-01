<?php
class utilityHelper{
	
	function buildBreadcrumb($detail = null, $path = array()){
		global $config, $pLang;
		
		$pathHTML = '';
		foreach($path as $url=>$title){
			$pathHTML .= "<h4 typeof='v:Breadcrumb'><a href='{$url}' title='{$title}' rel='v:url' property='v:title'>{$title}</a></h4>
						  <span>›</span>";
		}
		return <<<EOF
			<nav id='breadcrumb' xmlns:v='http://rdf.data-vocabulary.org/#'>
				<h4 typeof="v:Breadcrumb">
					<a href='{$config->base_url}' title='{$pLang->getWords('global_breadcumb_root','Chuyên trang phụ nữ')}' rel='v:url' property='v:title'>
						{$pLang->getWords('global_breadcumb_root','Chuyên trang phụ nữ')}
					</a>
				</h4>
				<span>›</span>
				{$pathHTML}
				<h5 typeof="v:Breadcrumb" property="v:title">{$detail}</h5>
				<div class='clear'></div>
			</nav>
EOF;
	}

}