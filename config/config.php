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
                                        'cipherSeed' => '76859309657453542496749683645',
                                        'test'  => array(
                                                        'test1' => array('134')
                                                    )
                                        ),
                        'locale'   => 'en'
    );

    public function getSetting($string = '') {
        return $this->retrieve($this->setting, $string);
    }

    private function retrieve($target, $string = '') {
        return Hash::get($target, $string);
    }
}