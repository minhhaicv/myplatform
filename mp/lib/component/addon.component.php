<?php
class addonComponent {

    public function get() {
        global $request;

        $channel = $request->channel;

        return $this->$channel();
    }

    function frontend() {
    }

    function backend() {
        $data = array(
                   'user' => array(
                                'username' => "Haitm",
                                'alias'    => 'pandog'
                            ),
                    'sidebar' => array(
                                    'Home' => '/home',
                                    'Post' => '/post'
                                )
                );
        return $data;
    }
}