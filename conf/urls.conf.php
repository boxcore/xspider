<?php if ( !defined('BOXCORE') ) exit('No direct script access allowed');

/*
  ----------------------------------------------------------------------
    URL请求资源配置
    eg:
    'url' => array(
        'c' => 'front/Article',         // 控制器文件名、类名
        'f' => 'show',                  // 方法名
        'p' => array(),                 // 参数
        'h' => array(),                 // 钩子
    );
  ----------------------------------------------------------------------
*/

return array(

    'default'      => array('c'=>'Index'),
    'api/get_article'       => array('c'=>'Api', 'f'=>'getArticle'),
    '404.html'     => array('c'=>'Notfind'),
);