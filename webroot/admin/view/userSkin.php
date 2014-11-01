<?php
class postSkin{
	
	function loginForm(){
		global $config, $html;
		
		$html->css(array('bootstrap.min'));
		
		$html->js(array('jquery-1.11.0', 'bootstrap.min'));
		
		
		$output .= <<<EOF
		        <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="login-panel panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Please Sign In</h3>
                                </div>
                                <div class="panel-body">
                                    <form action="{$config->base_url}user/login" method="post">
                                        <fieldset>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="E-mail" name="user[account]" type="text" autofocus value='account'>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Password" name="user[password]" type="password" value="password">
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                                </label>
                                            </div>
                                            <!-- Change this to a button or input when using this as a form -->
                                            <input type='submit' class="btn btn-lg btn-success btn-block" value="Login" />
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
EOF;
		return $output;
	}
	
}