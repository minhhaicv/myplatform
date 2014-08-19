<?php
class config {
    public $base_url = "";
    public $vars = array(
                'ajax_file'     => 'http://pdf.me/backend/',  // use mod_rewrite.
                'board_url'     => 'http://pdf.me',
                'cdn'           => 'http://pdf.me/cdn',//'http://cdn.pdf.me',// /cdntro thang den thu muc cdn o root
                'cdn.sub'       => '',
                'upload'        => 'http://media.pdf.me',//'http://media.ladysg.me',
                'upload_path'   => 'media/',
                'minify'        => '','http://cdn.pdf.me/minifier',
                'debug'         => 2,
        );
}