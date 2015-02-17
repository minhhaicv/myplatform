<?php
class securityAuth{

    protected function _salt() {
        return 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi';
    }

    public function hash($string = '', $type = 'sha1') {
        return Security::hash($string, $type, $this->_salt());
    }
}