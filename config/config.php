<?php
class config {

    public $vars = array(
                'cdn'           => 'http://mp.me/cdn',
                'board_url'     => 'http://mp.me',
                'debug'         => 2,
        );

    public $view = array(
                       'frontend' => 'frontend',
                       'backend'   => 'backend',
    );

    public $prefix = array('backend');

    public $setting = array(
                        'security' => array(
                                            'salt' => 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
                                        ),
                        'locale'   => 'en',

                        'authorize'=> array(
                                            'backend'
                                        ),
                        'ignore_authorize' => array(
                                                'backend' => array('user_login')
                        ),

    );

    public function get($string = null, $target = 'setting') {
        return Hash::get($this->$target, $string);
    }

    public function check($string = '', $target = 'setting') {
        return Hash::check($this->$target, $string);
    }
}