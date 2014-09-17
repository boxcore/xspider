<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

require APP . 'controls/BaseControl.php';

/**
 * 
 */

class IndexControl extends _BaseControl
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo src_url('in/dd');
    }
}