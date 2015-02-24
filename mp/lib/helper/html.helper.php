<?php
class htmlHelper{

    public function cdn(){
        global $request;

        return sprintf('%s/%s', Helper::config()->get('url.cdn'), $request->channel);
    }

    public function js($list = array(), $path = '') {
        if (empty($path)) {
            $path = 'js';
        }

        $path = self::cdn() .'/' . $path .'/';

        $result = '';
        foreach ($list as $item) {
            $src = $path . $item . '.js';

            $result .= '<script src="'.$src.'"></script>';
        }

        return $result;
    }


    public function css($list = array(), $path = '') {
        if (empty($path)) {
            $path = 'css';
        }

        $path = self::cdn() .'/' . $path .'/';

        $result = '';
        foreach ($list as $item) {
            $src = $path . $item . '.css';
            $result .= '<link rel="stylesheet" type="text/css" rel="stylesheet" href="'.$src.'" />';
        }

        return $result;
    }
}