<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

require APP . 'funcs/spider.fn.php';
require APP . 'et/phpQuery/phpQuery.php';
require APP . 'models/TaskModel.php';

/**
 * 采集公共控制器
 *
 * @author boxcore
 * @date   2014-09-17 11:04:36
 */

class _XspiderControl extends _Control
{
    public function __construct() {
        parent::__construct();
    }
}