<?php
class frontendSkin{
	function detail($obj, $option = array()){
		$this->format = Helper::getTrait('object');
		
		$BWHTML .= <<<EOF
				<div class='article'>
					<h1 class='item-title'>
			    		{$obj->getTitle()}
		    		</h1>
		    		<div class='section'>
		    			{$this->format->getContent($obj)}
					</div>
					<div class='clear'></div>
				</div>
EOF;
		return $BWHTML;
	}
	
	function category($option = array()){
		$this->formathepler = Helper::getHelper('format');
		$this->imagehepler = Helper::getHelper('image');
		$this->format = Helper::getTrait('object');
		$BWHTML .= <<<EOF
				<div class='category-section'>
				<foreach=" $option['objList'] as $post ">
				<div class='category-item'>
					<h4 class='item-title'>
			    		<a href='{$this->format->getURL($post, "posts")}' class='title' title='{$post->getTitle()}'>
			    			{$post->getTitle()}
			    		</a>
		    		</h4>
					<a href='{$this->format->getURL($post, "posts")}' class='item-image' title='{$post->getTitle()}'>
						{$this->imagehepler->displayImage($post->getFile(), 180, 120, 0, 1)}
					</a>
		    		<div class='item-section section'>
		    			{$this->formathepler->cutString($this->format->getContent($post), 380)}
					</div>
					<div class='clear'></div>
				</div>
				</foreach>
				</div>
				<if=" $option['paging'] ">
				<div class='paging'>
					{$option['paging']}
				</div>
				</if>
EOF;
		return $BWHTML;
	}

}