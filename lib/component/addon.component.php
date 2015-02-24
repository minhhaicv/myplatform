<?php
class addonComponent {

    public function get() {
        global $request;

        $channel = $request->channel;

        return $this->$channel();
    }

    function blank() {
    }

    function backend() {
        $menu = Helper::get('menu', 'entity');

        $data = array(
                    'menu_top'     => $menu->retrieve('top'),
                    'menu_left'     => $menu->retrieve('left')
                );

        return $data;
    }
}