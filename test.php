<?php

$arr = array(
    'node_info' => array(
        'charset' => 'gb2312',
        'note_name' => '网易新闻 - 国内新闻',
        'max_link' => '-1',
        'exptime' => '60',
        ),
    'list_rule' => array(
        /**
         * 获取链接方式 list_type
         * batch_link : 批量处理方式
         * text_link  : 添加文本链接
         * rss_link   : RSS订阅方式
         * mixed      : 三种混合启用
         */
        'list_type' => 'batch_link',
        'batch_link' => array(
            'regexurl' => 'http://news.163.com/special/0001124J/guoneinews_(*).html',
            'start_id' => "02",
            'end_id' => "10",
            'musthas' => 'news.163.com',
            'nothas' => 'special',
            ), 
        'text_link' => array(
            'http://news.163.com/domestic/',
            'http://news.163.com/domestic2/',
            ),
        'rss_link' => array(
            'http://news.163.com/feed/',
            'http://news.163.com/rss.php',
            ),
        ),
    'list_area' => array(
        /**
         * 获取列表的区块方式 match_type
         * regex : 正则匹配
         * query : 使用phpQuery
         * xPath : 使用xPath方式
         * none  : 不使用匹配
         */
        'match_type' => 'regex',
        'regex' => array(
            'rules' => '#<div class="area-left">(.*?)<div class="area-right">#ig',
            ),
        'query' => array(
            'rules' => 'body div.area-left',
            ),
        'xpath' => array(
            'rules' => 'html>body>div.test>p>div.left',
            ),
        ),
    );

$json = json_encode($arr,true);
echo $json;