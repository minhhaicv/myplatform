<?php
class pathHelper{

    public function cdn(){
        global $config, $request;

        return sprintf('%s/%s', $config->vars['cdn'], $request->channel);
    }

    public function import($module = '', $filename = '', $type = '') {
        $controller = array('model', 'entity', 'controller');

        if(in_array($type, $controller)) {
            $path = 'controller' . DS  . $module . DS . $filename . '.php';
        } else {
            $path = 'lib' . DS . $type;
            if($type == 'package') {
                $path .= DS . $module;
            }

            $path = $path . DS . $filename . '.php';
        }

        if(file_exists(ROOT . DS . $path)) {
            $path = ROOT . DS . $path;
        } else {
            $path = MP . $path;
        }

        return $path;
    }
}