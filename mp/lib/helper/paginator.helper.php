<?php

class PaginatorHelper {

    public function paginate($options = array(), $model = null, &$pager = array()) {
        $default = array(
                        'page' => 1,
                        'limit' => 10,
        );

        $options = array_merge($default, $options);
        extract($options);

        $results = $model->find($options, 'all');

        if (!$results) {
            $count = 0;
        } elseif ($page === 1 && count($results) < $limit) {
            $count = count($results);
        } else {
            $count = $model->find($options, 'count');
        }

        $pageCount = (int)ceil($count / $limit);
        $requestedPage = $page;
        $page = max(min($page, $pageCount), 1);

        if ($requestedPage > $page) {
            throw new NotFoundException();
        }

        if(isset($pager) && $pageCount > 1) {
            $pager = $this->_pager(array('current' => $page, 'count' => $pageCount));
        }

        return $results;
    }

    private function _pager($params) {
        $result = array();

        $modulus = 4;

        $start = 1;
        $end = $params['count'];

        if($params['count'] > $modulus) {
            $half = (int)($modulus / 2);
            $end = $params['current'] + $half;

            if ($end > $params['count']) {
                $end = $params['count'];
            }
            $start = $params['current'] - ($modulus - ($end - $params['current']));
            if ($start <= 1) {
                $start = 1;
                $end = $params['current'] + ($modulus - $params['current']) + 1;
            }
        }

        if($params['current'] >= $modulus) {
            $result[] = array('display' => '&laquo;', 'page' => 1, 'target' => false);
        }

        while($start <= $end) {
            $result[] = array('display' => $start, 'page' => $start, 'target' => ($start == $params['current']));

            $start++;
        }

        if($params['current'] < ($params['count'] - $half)) {
            $result[] = array('display' => '&raquo;', 'page' => $params['count'], 'target' => false);
        }

        return $result;
    }
}
