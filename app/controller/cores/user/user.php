<?php
class user extends Model {

    function __construct($option=array()){
		parent::__construct('user', 'User');
	}
}