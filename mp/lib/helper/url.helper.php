<?php

class urlHelper {

    public function get($params = array()) {
        global $request;

        $url = $request->getBaseUrl() . '/' . $request->prefix;


        foreach($request->query as $k => $v) {
            if($k === 'action' || $k === 'module') continue;
            if(empty($v) && !isset($params[$k])) continue;

            if(strpos($v, ':') !== false) {
                list($k, $v) = explode(':', $v);
            }

            if(isset($params[$k])) {
                $url .= '/' . $k . ':' . $params[$k];
                unset($params[$k]);
            }
             else {
                $url .= '/' . $v;
            }
        }

        foreach($params as $k => $v) {
            $url .= '/' . $k . ':' . $params[$k];
        }

        return $url;
    }

    public function notfound() {
        global $request;

        return $request->getBaseUrl() . '/error/notfound';
    }

    public function generate($type = 'list', $id = '') {
        global $request;

        $url = $request->getBaseUrl() . '/' . $request->prefix . '/' . $request->query['module'] . '/' . $type;

        if($type == 'edit')  $url .= '/' . $id;

        return $url;
    }

    public function seo($target = array(), $type='', $prefix = '') {
        global $request;

        $url = $prefix . $request->query['module'] . '/' . $type ;

        return $url . '/' . $target['id'];
    }

    public function category($type = 'list', $option = array(), $target = array()) {
        global $request;

        $url = $request->getBaseUrl() . '/' . $request->prefix . '/' . $request->query['module'] . '/' . $type;

        if(empty($option['branch']) == false) {
            $url .= '/' . $option['branch'];
        }

        switch ($type) {
            case 'main':
                $url .= empty($target['slug']) ? '' : '/edit:' . $target['slug'];
                break;
            case 'branch':
                $url .= empty($target['slug']) ? '' : '/' . $target['slug'];
                break;
            case 'edit':
                $url .= '/' . $target['id'];
                break;
        }

        return $url;
    }
}
