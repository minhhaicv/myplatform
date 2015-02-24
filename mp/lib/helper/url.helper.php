<?php

class urlHelper {

    public function get($params = array()) {
        global $request;

        $url = $request->baseUrl() . '/' . $request->prefix;

        foreach ($request->query as $k => $v) {
            if ($k === 'action' || $k === 'module') {
                continue;
            }

            if (empty($v) && !isset($params[$k])) {
                continue;
            }

            if (strpos($v, ':') !== false) {
                list($k, $v) = explode(':', $v);
            }

            if (isset($params[$k])) {
                $url .= '/' . $k . ':' . $params[$k];
                unset($params[$k]);
            } else {
                $url .= '/' . $v;
            }
        }

        foreach ($params as $k => $v) {
            $url .= '/' . $k . ':' . $params[$k];
        }

        return $url;
    }

    public function generate($url = 'list') {
        global $request;

        return $request->baseUrl() . '/' . $request->prefix . '/' . $request->query['module'] . '/' . $url;
    }

    public function extend($url = '') {
        global $request;

        $return = $request->baseUrl() . '/';
        if (empty($request->prefix) == false) {
            $return .= $request->prefix . '/';
        }

        return $return . $url;
    }
}