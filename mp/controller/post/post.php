<?php


class post extends Model {

    function __construct(){
        parent::__construct('post', 'post');
    }

    function __destruct(){
        unset($this);
    }

}