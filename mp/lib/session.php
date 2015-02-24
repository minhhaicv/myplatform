<?php
class Session {

    public function __construct() {
        session_start();
    }

    static function check($name = ''){
        if (empty($name)) {
            return false;
        }

        return Hash::get($_SESSION, $name) !== null;
    }

    static function write($name, $value = null) {
        if (empty($name)) {
            return false;
        }

        $write = $name;
        if (!is_array($name)) {
            $write = array($name => $value);
        }

        foreach ($write as $key => $val) {
            self::_overwrite($_SESSION, Hash::insert($_SESSION, $key, $val));
            if (Hash::get($_SESSION, $key) !== $val) {
                return false;
            }
        }

        return true;
    }

    static function read($name='') {
        if (empty($name) && $name !== null) {
            return null;
        }

        if ($name === null) {
            return $_SESSION;
        }

        $result = Hash::get($_SESSION, $name);

        if (isset($result)) {
            return $result;
        }

        return null;
    }

    static function delete($key=''){
        if (self::check($name)) {
            self::_overwrite($_SESSION, Hash::remove($_SESSION, $name));
            return !self::check($name);
        }
        return false;
    }

    protected static function _overwrite(&$old, $new) {
        if (!empty($old)) {
            foreach ($old as $key => $var) {
                if (!isset($new[$key])) {
                    unset($old[$key]);
                }
            }
        }
        foreach ($new as $key => $var) {
            $old[$key] = $var;
        }
    }

    public static function destroy() {
        session_destroy();

        $_SESSION = null;
    }
}