<?php
class postSkin{
	
	function layout(){
		$output .= <<<EOF
		        {$this->header}
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
   {$this->sidebar}
    
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content">
    <div class="content">
      <ul class="breadcrumb">
        <li>
          <p>YOU ARE HERE</p>
        </li>
        <li><a href="#" class="active">Tables</a> </li>
      </ul>
    
      {$this->main_content}
    </div>
  </div>
</div>
<!-- END CONTAINER -->
EOF;
		return $output;
	}
	
}