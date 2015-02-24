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
}