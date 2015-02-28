<?php

class fileEntity extends entity {
    function __construct($model = 'file', $table = 'file', $alias = 'file') {
        parent::__construct($model, $table, $alias);
    }

    public function upload() {
        $result = array();

        $helper = Helper::get('file');

        foreach ($_FILES as $key => $item) {
            if (is_array($item['tmp_name'])) {
                foreach ($_FILES[$key]['tmp_name'] as $index => $ignore) {
                    $info['name']       = $_FILES[$key]['name'][$index];
                    $info['type']       = $_FILES[$key]['type'][$index];
                    $info['error']      = $_FILES[$key]['error'][$index];
                    $info['tmp_name']   = $_FILES[$key]['tmp_name'][$index];
                    $info['size']       = $_FILES[$key]['size'][$index];

                    $data[] = $this->__upload($helper, $info, $key);
                }
            } else {
                $info = $_FILES[$key];
                $data[] = $this->__upload($helper, $info, $key);
            }
        }

        foreach ($data as $item) {
            $this->model->save($item);

            $item['id'] = $this->model->lastInsertId();

            $url = Helper::config()->get('url.media') . '/' . $item['directory'] . '/' . $item['name'];
            $item['thumbnail'] = $url;


            $return[$item['container']][] = $item;
        }

        return $return;
    }

    private function __upload($helper, $info, $key) {
        $filename = $ext = '';

        $filename = Helper::get('string')->block($info['name'], '', array('_' => '-'));

        if (strpos($filename, '.') !== false) {
            list($filename, $ext) = explode('.', $filename);

            $ext = '.'.$ext;
        }
        $filename = $filename.'-'.date('YmdHis').$ext;

        $tmp = Helper::config()->get('upload.directory.'.$key);
        $directory = is_null($tmp) ? 'img' : $tmp;

        $destination = MEDIA.$directory;

        $error = null;

        $info['filename'] = $filename;
        $flag = $helper->upload($info, $destination, $error);

        return array(
                        'error'     => $error,
                        'success'   => (boolean)$flag,
                        'container' => $key,
                        'real_name' => $info['name'],
                        'directory' => $directory,
                        'name'      => $filename,
                        'size'      => $info['size']
        );
    }

    public function delete($id = array(), &$warning = array()) {
        $target = implode(',', $id);

        $alias = $this->model->getAlias();
        $condition = "{$alias}.id IN (" . $target . ")";

        $option = array(
                        'select' => "{$alias}.id, concat({$alias}.directory, '/', {$alias}.name) as fullpath",
                        'where'  => $condition,
                        'order'  => "{$alias}.id asc",
        );

        $history = $this->model->find($option);

        $prefix = Helper::config()->get('upload.location') . '/';

        $helper = Helper::get('file');
        foreach ( $history as $key => $item) {
            $path = $prefix . $item[0]['fullpath'];

            if ($helper->delete($path) === false) {
                $warning[$key] = $path;
            }
        }

        $this->model->delete($condition);

        return empty($warning);
    }
}