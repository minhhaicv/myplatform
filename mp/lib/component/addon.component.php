<?php
class addonComponent {

    public function get() {
        global $request;

        $branch = $request->branch;

        return $this->$branch();
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