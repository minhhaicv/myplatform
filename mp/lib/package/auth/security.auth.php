<?php
class securityAuth{

    protected function salt() {
        return 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi';
    }

    public function hash($string = '', $type = 'sha1') {
        return Helper::get('security')->hash($string, $type, $this->salt());
    }
}