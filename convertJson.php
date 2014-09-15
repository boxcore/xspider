<?php

/**
 * 生成json文件工具
 *
 *
 *
 *
 */

$demo_json = '
{
    "node_info": {
        "charset": "gb2312",  // 目标网站字符编码
        "node_name": "网易新闻 - 国内新闻",  // 采集节点名称
        "max_link": "-1", // 获取链接数量, -1为无限个
        "exptime": "60" // 执行采集的过期时间
    },
    "list_rule": {
        "list_type": "mixed",  // 获取列表页面规则,可以指定 batch_link, text_link和rss_link
        "batch_link": {
            "regexurl": "http://news.163.com/special/0001124J/guoneinews_(*).html",
            "start_id": "02", //开始id
            "end_id": "10", // 结束id
            "id_len": "2" // 数字定长
        },
        "text_link": [//链接列表
            "http://news.163.com/domestic/"
        ],
        "rss_link": [ ]
    },
    "list_area": { //获取链接规则
        "match_type": "none", //抓取链接的类型,可指定regex,query,xpath和none,如果指定为none则在preg_links中获取正则匹配的链接.
        "preg_links": "#(http://news\\.163\\.com/[\\d]+/[\\d]+/[\\d]+/.+?\\.html)#i",
        "regex": {
            "rules": "#<div class=\"area\\-left\">(.+)<div class=\"area\\-right\">#iUs"
        },
        "query": {
            "rules": "body div.area-left"
        },
        "xpath": {
            "rules": "html>body>div.test>p>div.left"
        }
    },
    "musthas": "news.163.com", // 必须要包含的字符串
    "nothas": "special" // 不包含的字符串
}

';

$str_json = '';

$arr_get_links = array(
    'node_info' => array(
        'charset' => 'utf-8',
        'node_name' => '糗事百科',
        'max_link' => '-1',
        'exptime' => '60'
    ),
    'list_rule' => array(
        'list_type' => 'batch_link', // 指定获取链接的类型: mixed, batch_link,text_link,rss_link
        'batch_link' => array(
            'regexurl' => 'http://www.qiushibaike.com/8hr/page/(*)',
            "start_id"=>"1", //开始id
            "end_id"=>"10", // 结束id
            "id_len"=>"1", // 数字定长
        ),
        'text_link' => array(
            'http://www.qiushibaike.com/',
            'http://www.qiushibaike.com/8hr/page/2',
            'http://www.qiushibaike.com/8hr/page/3',
        ),
        'rss_link' => array(),
    ),
    'list_area' => array(
        'match_type' => 'none', // none:不限定区域;  regex定义正则获取的区域; query:使用php-query获取; xpath: 使用xpath获取
        'preg_links' => '#(/article/[\\d]+\\?list=8hr)#i', //preg_links: 获取链接的正则, 必填
        'prex_domain' => 'http://www.qiushibaike.com',
        'regex' => array(
            'rules' => '#<div class=\"area\\-left\">(.+)<div class=\"area\\-right\">#iUs',
        ),
        'query' => array(
            'rules' => 'body div.area-left',
        ),
        'xpath' => array(
            'rules' => 'html>body>div.test>p>div.left',
        ),
    ),
    'musthas' => '8hr', // 必须要包含的字符串
    'nothas' => '', // 不包含的字符串
);

$arr_get_content = array(
    'charset' => 'utf-8',
    'type' => 'regex', // 获取内容的类型 regex:正则获取
    'regex' => array(
        'content' => array(
            '#<div class="content" title=".*?">(.*?)</div>#is',
        ),
        'filter' => array(
            '',
        ),
        'name' => array(
            'content',
        ),
        'pic' => '#<div class="thumb">.*?<img src="(.*?)".*?</div>#is', // 图片由哪里获取,来自rules['name']中
    ),
    'replace' => array(
        'pattern' => array('#http://pic\.qiushibaike\.com/#','#糗事百科#','#糗友#'),
        'replacement' => array('http://doucaor.qiniudn.com/','逗槽儿','逗友'),
    ),
);

$json = json_encode($arr_get_content,true);

echo "\n\n\n";
print_r( json_decode($json) );
echo "\n\n\n";
print_r($json);
echo "\n\n\n";
// /article/80656385?list=8hr