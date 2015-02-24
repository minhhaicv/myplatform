<?php
class config {

    //channel -> folder
    public $view = array(
                       'blank' => 'default',
                       'backend'   => 'backend',
    );

    public $prefix = array('backend');

    public $setting = array(
                        'debug'     => 2,

                        'locale'   => 'en',

                        'security' => array(
                                            'salt' => 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
                        ),

                        'authorize'=> array(
                                            'backend'
                        ),

                        'ignore_authorize' => array(
                                                'backend' => array(
                                                                'user'  => array('login', 'logout'),
                                                                'error' => array()
                                                )
                        ),

                        'upload' => array(
                                        'max_size' => 5,
                                        'directory' => array(
                                                        'post' => 'bai-viet',
                                                        'file' => 'bai-viet'
                                        ),

                        ),

                        'url'   => array(
                                        'media'     => 'http://mp.me/media',
                                        'cdn'       => 'http://www.mp.me/cdn',
                        ),

                        'website' => array(
                                        'page_title' => 'Chuyên trang phụ nữ',
                        )

    );

    public function get($string = null, $target = 'setting') {
        return Hash::get($this->$target, $string);
    }

    public function check($string = '', $target = 'setting') {
        return Hash::check($this->$target, $string);
    }
}