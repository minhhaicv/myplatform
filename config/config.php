<?php
class config {
    public $base_url = "";
    public $vars = array(
                'ajax_file'     => 'http://mp.me/backend/',  // use mod_rewrite.
                'board_url'     => 'http://mp.me',
                'cdn'           => 'http://mp.me/cdn',//'http://cdn.pdf.me',// /cdntro thang den thu muc cdn o root
                'cdn.sub'       => '',
                'upload'        => 'http://media.mp.me',//'http://media.ladysg.me',
                'upload_path'   => 'media/',
                'minify'        => '','http://cdn.mp.me/minifier',
                'debug'         => 2,
        );
}