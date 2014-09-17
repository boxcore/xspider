<?php

// 默认超时
set_time_limit( 0 );

// 定义应用目录
define('APP',  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// 载入框架引导文件
require APP.'system/_shell.php';
require APP . 'funcs/spider.fn.php';
require APP . 'models/TaskModel.php';
require APP . 'et/phpQuery/phpQuery.php';

//获取链接列表  http://www.tomdurrie.com/search.php?page=380
$links = get_batch_link('http://www.tomdurrie.com/search.php?page=(*)', 1, 3, 1);
if(!empty($links)){
    foreach($links as $target_url){
        /**
         * 获取维美达链接列表
         */
        echo "正在获取链接{$target_url}下的产品链接\n";
        phpQuery::newDocumentFile($target_url);
        $goods_list = pq('.hoverlist');

        $lists_tmp = array();
        foreach($goods_list as $li){
            $lists_tmp[] = array(
                'url' => pq($li)->find('a')->attr('href'),
                'thumb_img_org' => pq($li)->find('img')->attr('src'),
            );
        }

        // 探测链接失败
        if( empty( $lists_tmp ) ) {
            system("echo -e '探测链接列表失败: \\033[31m".$target_url."\\033[0m'");
            $result_errr = insert_log( $target_url, '探测链接列表失败' );
        }else{
            insert_ec_urls( $lists_tmp, 0, true, 'spider_ecshop_url' );
        }
    }
}