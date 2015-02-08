<?php
class path{

    static public function load($type = '', $target = '') {
        $result = 'lib' . DS;

        switch($type) {
            case 'component':
            case 'behavior':
            case 'helper':
            case 'utility':
                $result .= $type . DS;
                break;
            case 'package':
                $result .= $type . DS . $target . DS;
                break;
            case 'module':
                $result = 'controller' . DS . $target . DS;
                break;
            default:
                break;
        }

        return $result;
    }
}