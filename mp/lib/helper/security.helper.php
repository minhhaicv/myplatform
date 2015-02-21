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
            $ignore = Helper::config()->get('ignore_authorize.'.$prefix);

            if (empty($ignore) == false) {
                if (isset($ignore[$request->query['module']])) {
                    if (empty($ignore[$request->query['module']])) {
                        return true;
                    }

                    if (in_array($request->query['action'], $ignore[$request->query['module']])) {
                        return true;
                    }
                }
            }

            return Session::check('auth.user.id');
        }

        return true;
    }
}