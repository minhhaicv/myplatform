<?php

class urlHelper {


    public function get($params = array()) {
        global $request;

        $url = $request->getBaseUrl() . '/' . $request->prefix;

        foreach($request->query as $k => $v) {
            if(empty($v) && !isset($params[$k])) continue;

            if(isset($params[$k])) {
                $url .= '/' . $params[$k];
            } else {
                $url .= '/' . $v;
            }
        }

        return $url;
    }
}
