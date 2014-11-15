<?php
class frontendSkin{
	
	
	function category($option = array()){
		$map = array('fa-minus', 'fa-plus');
		
		$output .= <<<EOF
		<div class="row-fluid">
            <div class="span12">
              <div class="grid simple ">
                <div class="grid-title">
                  <h4><span class="semi-bold">Post</span></h4>
                  <div class="pull-right">
                      <a class="btn btn-primary" href="{$this->config->base_url}post/add">Add</a>
                      <a class="btn btn-danger" href="">Delete</a>
                  </div>
                </div>
                <div class="grid-body">   
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width='25'><input type='checkbox' /></th>
                                <th width='25'>&nbsp;</th>
                                <th>Title</th>
                                <th class='col-lg-2'>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <foreach=" $option['list'] as $item ">
                            <tr>
                                <td><input type='checkbox' /></th>
                                <td><i class="fa {$map[$item[$this->alias]['status']]}"></i></td>
                                <td>
                                    <a class="" href="{$this->config->base_url}post/edit/{$item[$this->alias]['id']}" title="{$item[$this->alias]['title']}">
                                        {$item[$this->alias]['title']}
                                    </a>
                                </td>
                                <td>{$item[$this->alias]['created']}</td>
                            </tr>
                            </foreach>
                        </tbody>
                    </table>
                </div>
              </div>
	        </div>
        </div>
EOF;
		return $output;
	}
	
	function form($item = array()) {
	    global $html, $config;

	    $this->obj = Helper::getTrait('object');
	    $html->css(
	            array(
                    'bootstrap-summernote/summernote',
	            ),
	            0,
	            'package'
	    );
	    
	    $html->js(
	            array(
                    'bootstrap-summernote/summernote.min',
	            ),
	            0,
	            'package'
	    );
	    
	    $output .= <<<EOF
	    <style>
	       .clear{
	           clear:both;
	       }
	       .grid-title .h3 {
	           float:left;
	       }
	    </style>
        <div class="row">
            <div class="col-md-12">
              <div class="grid simple">
                <div class="grid-title">
                    <div class='h3'><span class="semi-bold">Post</span></div>
                    <div class='tools'>
                        <button id="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class='clear'></div>
                </div>
	            <form action='{$this->config->base_url}post/edit/' method="post" id='form'>
	            <input type="hidden" value="" name="post[id]" />
	            
                <div class="grid-body">
                  <div class="row">
	                    
	                <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">Title</label>
                        <div class="controls">
                          <input type="text" class="form-control" name="post[title]" value='Công việc của những phu đào huyệt dũng cảm nhất thế giới'>
                        </div>
                      </div>
                                  
                      <div class="form-group">
                        <label class="form-label">Content</label>
                        <div class="controls">
                            <textarea class="editor" name="post[content]">{$this->obj->decode(@$item[$this->alias]['content'])}</textarea>
                        </div>
                      </div>
                    </div>
	                    
                    <div class="col-md-8 col-sm-8 col-xs-8">
                      <div class="form-group">
                        <label class="form-label">URL</label>
                        <div class="controls">
                          <input type="text" class="form-control" name="seo[alias]" value='url'>
                        </div>
                      </div>
	                    
                      <div class="form-group">
                        <label class="form-label">Canotical</label>
                        <div class="controls">
                          <input type="text" class="form-control" name="seo[canonical]" value='canonical'>
                        </div>
                      </div>
	                    
	                  <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <div class="controls">
                          <input type="text" class="form-control" name="seo[title]" value='title'>
                        </div>
                      </div>  
	                    
	                  <div class="form-group">
                        <label class="form-label">Meta keywords</label>
                        <div class="controls">
                          <input type="text" class="form-control" name="seo[keyword]" value='keyword'>
                        </div>
                      </div>

	                  <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <div class="controls">
                          <textarea class="form-control" name="seo[desc]">desc</textarea>
                        </div>
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
	      <script>
$(document).ready(function() {
    $('#submit').click(function() {
	   $('#form').submit();                    
	});
	                    
	                    
  $('.editor').summernote({
    height: 300,
    minHeight: 300,   
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['link', ['link', 'video', 'picture']],
        ['view', ['fullscreen', 'codeview']],
      ],
	});
});
	      </script>
EOF;
	}
}