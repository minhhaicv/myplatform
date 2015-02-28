<?php
class securityHelper{

    protected function _salt() {
        return Helper::config()->get('security.salt');
    }

    public function hash($string = '', $type = 'sha1') {
        return Security::hash($string, $type, $this->_salt());
    }

    public function authenticate() {
        global $request;

        $channel = $request->channel;

        if (in_array($channel, Helper::config()->get('authorize'))) {
            $ignore = Helper::config()->get('ignore_authorize.'.$channel);

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

            return Helper::login()->loggedIn();
        }

        return true;
    }
}