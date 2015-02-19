<?php
class securityHelper{

    protected function _salt() {
        return 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi';
    }

    public function hash($string = '', $type = 'sha1') {
        return Security::hash($string, $type, $this->_salt());
    }

    public function authenticate() {
        global $request;

        $prefix = $request->prefix;

        if (in_array($prefix, Helper::config()->get('authorize'))) {
            $match = $request->query['module'].'_'.$request->query['action'];

            $ignore = Helper::config()->get('ignore_authorize.'.$prefix);
            if (in_array($match, $ignore)) {
                return true;
            }

            return Session::check('auth.user.id');
        }

        return true;
    }
}