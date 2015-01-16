<?php
class htmlHelper{

    public function js($list = array(), $path = '') {
        if(empty($path)) $path = 'js';

        $path = helper::get('path', 'helper')->cdn() .'/' . $path .'/';

        $result = '';
        foreach($list as $item) {
            $src = $path . $item . '.js';

            $result .= '<script src="'.$src.'"></script>';
        }

        return $result;
    }


    public function css($list = array(), $path = '') {
        if(empty($path)) $path = 'css';

        $path = helper::get('path', 'helper')->cdn() .'/' . $path .'/';

        $result = '';
        foreach($list as $item) {
            $src = $path . $item . '.css';
            $result .= '<link rel="stylesheet" type="text/css" rel="stylesheet" href="'.$src.'" />';
        }
        return $result;
    }
}