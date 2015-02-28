<?php

class fileHelper {

    public function upload($data = array(), $destination = '', &$error = '') {
        if (!is_dir($destination)) {
            mkdir($destination, 755);
        }

        $destination = $destination . '/' . $data['filename'];
        return move_uploaded_file($data['tmp_name'], $destination);
    }

    public function delete($path = '') {
        return unlink($path);
    }
}