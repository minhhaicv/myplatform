<?php
class session {

    public function test() {
        print "<pre>";
        print_r('session lib');
        print "</pre>";
    }

    public function __construct() {
        session_start();
    }

    static function check($key=''){
        if($key) return false;

        $eval ="\$return = \$_SESSION";
        foreach($temp as $field) {
            $eval = $eval."['".$field."']";
        }
        eval($eval.';');
        return isset($return);
    }

    static function write($key='', $val=''){
        if(!$key) return false;
        $temp = explode('.', $key);

        $eval ="\$_SESSION";
        foreach($temp as $field) {
            $eval = $eval."['".$field."']";
        }

        $eval .= "=\$val;";
        eval($eval);
    }

    static function read($key='') {
        if(!$key) return false;
        $temp = explode('.', $key);

        $eval ="\$return = \$_SESSION";
        foreach($temp as $field) {
            $eval = $eval."['".$field."']";
        }
        eval($eval.';');

        return $return;
    }

    static function delete($key=''){
        if(!$key) return false;

        $eval ="unset(\$_SESSION";
        foreach(explode(".", trim($key, '.')) as $field) {
            $eval = $eval."['".$field."']";
        }
        eval($eval.');');
    }
}