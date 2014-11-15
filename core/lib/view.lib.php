<?php

class View {
	public function render($output = '', $layout = '') {
		global $config, $app, $html;
		
		if($layout) {
			global $template;
// 			$template->loadGlobalTemplate();
			
// 			$app->requireFile(LIBS_PATH . 'addon.lib.php');
// 			$addon = new Addon();
			
// 			$template->global_template->main_content = $output;
			
// 			$output = $template->global_template->layout();
		}
		
		$this->shortcut = $config->vars['board_url'] . "/favicon.ico";
		
// 		$temp 				= $html->loadJS();
// 		$this->js_top 		= isset($temp[0]) ? $temp[0] : '';
// 		$this->js_bottom 	= isset($temp[1]) ? $temp[1] : '';
		
// 		$temp 				= $html->loadCSS();
// 		$this->style_top 	= isset($temp[0]) ? $temp[0] : '';
// 		$this->style_bottom= isset($temp[1]) ? $temp[1] : '';
		
		$this->_wrapper($output);
		
		$this->_show();
		$app->finish();
	}
	
	private function _wrapper($output = '') {
	    global $meta, $app,$template;

	    //tmp;
	    $lang = $app->language();
	
	    $metaTag = $canonical = $paginator = "";
	
	    $title = $meta['title'];
	
	    $meta = <<<EOF
			<meta content="{$title}" name="title" />
			<meta content="{$meta['keyword']}" name="keyword" />
			<meta content="{$meta['desc']}" name="description" />
EOF;
	
	    if(!empty($meta['canonical'])) $canonical = "<link rel='canonical' href='{$meta['canonical']}' />";
	    if(!empty($meta['paginator'])) $paginator = $meta['paginator'];
	
	    $output = <<<EOF
	       <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
<title>{{ title }}</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta content="Chuyên trang phụ nữ" name="author" />
<meta content="Chuyên trang phụ nữ" name="copyright" />
<meta content="follow, index" name="robots" />

<meta property="fb:app_id" content="118406908367197"/>
<meta property="fb:admins" content="100002340136737"/>

<link href="{$this->shortcut}" rel="shortcut icon" type="image/x-icon" />

</head>
<body>
{% for i in range(0, 3) %}
    {{ i }},
{% endfor %}

{{ pandog }}
        <br />
        {{ pandog }}
</body>
</html>    
EOF;
	    echo $template->render($output, array('title' => $title, 'pandog' => 'variable'));
	}

	private function _show(){
	    
	    $buffer = ob_get_contents();
	    ob_end_clean();
	    ob_start ('ob_gzhandler');
	  //  print $this->wrapper;
	
	    print $buffer;
	}
}