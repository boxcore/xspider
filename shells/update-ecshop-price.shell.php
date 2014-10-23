<?php

// 默认超时
set_time_limit( 0 );

// 定义应用目录
define('APP',  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// 载入框架引导文件
require APP . 'system/_shell.php';
require APP . 'funcs/spider.fn.php';
require APP . 'funcs/ecshop.fn.php';
require APP . 'models/ContentModel.php';
require APP . 'et/phpQuery/phpQuery.php';

require APP . 'conf/52milan.conf.php';

echo "开始获取队列\n";
$table_name = 'spider_ecshop_url';
$contentModel = new ContentModel();

$configs = array(
    'need_push' => 'no',
    'price'     => false,
    'start_goods_id' => 63768, // 63767 后面开始进行第二次采集
    // 'url'=>'http://www.tomdurrie.com/ding-g63634.html',
);

$url_list = $contentModel->getUrlList($configs, 'LIMIT 15000 ', $table_name);
$url_count = count($url_list);

echo "获取到{$url_count}条要更新价格的内容... \n";

if(!empty($url_list)){


    foreach($url_list as $v){
        
        if( $v['url'] ) {
            echo "正在获取{$v['url']}的内容\n";

            /**
             * 获取单个产品内容
             */
            phpQuery::newDocumentFile($v['url']);
            $goods_id = intval( pq('input[name="id"]')->attr('value') );
            
            // 说明源id大于47900是无水印的 http://www.tomdurrie.com/search.php?page=380 前判读吧..
            // 63767 后面开始进行第二次采集
            if($goods_id>=$configs['start_goods_id']){

                $price_tmp = trim( pq('#ECS_SHOPPRICE')->html() );
                if( preg_match('(\d+)', $price_tmp, $match) ) {
                    $price = $match[0];
                    $market_price = (int)(floor($price/0.57));
                }else{
                    $price =0;
                }

                echo "正在修改产品{$v['goods_id']}价格\n";
                update($table_name, array('price'=>$price,'update_time'=> date('Y-m-d H:i:s')), array('id'=>$v['id']));

                // 修改ecshop中的产品价格
                $result = false;
                // if( ($v['price'] == 0) && ($price != 0) ) {
                //     if($v['goods_id']>0){
                //         $result = update('ecs_goods', array('shop_price'=>$price, 'market_price'=>$market_price ), array('goods_id'=>$v['goods_id']), '51milan');
                //     }
                // }

                if($result){
                    echo "产品{$v['goods_id']}更新价格成功\n";
                }
            }

        }else{
            system("echo -e '匹配内容为空: \\033[32m".$v['url']."\\033[0m'");
        }

    }
}

/**
mysqldump -uroot -p123456 boxcore_robotx ecs_goods --default-character-set=utf8 --extended-insert=false -c --where=" goods_id>=63768" > ecs_goods.sql

mysqldump -uroot -p123456 boxcore_robotx ecs_goods_gallery --default-character-set=utf8 --extended-insert=false -c --where=" goods_id>=63768" > ecs_goods_gallery.sql




 */