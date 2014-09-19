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

echo "开始获取队列\n";
$table_name = 'spider_ecshop_url';
$contentModel = new ContentModel();

$configs = array(
    'need_push' => 'yes',
    // 'url'=>'http://www.tomdurrie.com/ding-g63634.html',
);

$url_list = $contentModel->getUrlList($configs, 'LIMIT 15000 ', $table_name);
$url_count = count($url_list);

echo "获取到{$url_count}条要采集的内容... \n";

if(!empty($url_list)){

    // $url_info = get_line(prepare('select * from task_list where id=?i limit 1', array($ko)));

    foreach($url_list as $v){
        
        if( $v['url'] ) {

            /**
             * 获取单个产品内容
             */
            phpQuery::newDocumentFile($v['url']);
            $goods_id = intval( pq('input[name="id"]')->attr('value') );
            
            // 说明源id大于47900是无水印的 http://www.tomdurrie.com/search.php?page=380 前判读吧..
            if($goods_id>47900){

                // 删除旧产品数据和相册数据
                delete( 'ecs_goods', array('goods_id'=>$goods_id) );
                delete( 'ecs_goods_gallery', array( 'goods_id'=>$goods_id ) );

                $cat_name = trim( pq('#ur_here>.f_l>a:eq(1)')->html() );
                $brand_name = trim( pq('.props>dl:eq(1)>dd')->html() );
                $price_tmp = trim( pq('#ECS_SHOPPRICE')->html() );
                if( preg_match('(\d+)', $price_tmp, $match) ) {
                    $price = $match[0];
                }else{
                    $price =0;
                }


                $goods_info = array(
                    'goods_id'     => $goods_id,
                    'cat_id'       => get_cat_id( $cat_name ),
                    'goods_sn'     => trim( pq('.props>dl:eq(0)>dd')->html() ),
                    'goods_name'   => trim( pq('h1')->html() ),
                    'goods_desc'   => '<table>' . pq('div>table')->html() . '</table>',
                    'brand_id'     => get_brand_id( $brand_name ),
                    'goods_number' => 9999,
                    'market_price' => (int)(floor($price/0.57)),
                    'shop_price'   => $price,
                    'goods_thumb'  => 'http://baobaopic.qiniudn.com/'.$v['thumb_img_org'],
                );

                $result = insert('ecs_goods', $goods_info);

                /**
                 * 获取相册
                 * 
                 */
                $gallerys_tmp = pq('.gallery>#demo>#demo1>ul>li');
                $goods_gallerys = array();
                foreach($gallerys_tmp as $li){
                    $img_url = trim(pq($li)->find('a')->attr('rev'));
                    $thumb_url = trim(pq($li)->find('img')->attr('src'));

                    $goods_gallerys[] = array(
                        'goods_id'  => $goods_id,
                        'img_url'   => !empty($img_url) ? 'http://baobaopic.qiniudn.com/'.$img_url : '',
                        'thumb_url' => !empty($thumb_url) ? 'http://baobaopic.qiniudn.com/'.$thumb_url : '',
                    );
                    
                }

                if( isset($goods_gallerys[0]['img_url']) && !empty($goods_gallerys[0]['img_url']) ){
                    $gallery_result = insert_batch('ecs_goods_gallery', $goods_gallerys);
                }

                if($result){
                    update($table_name, array('need_push'=>'no', 'goods_id'=>$goods_id), array('hash'=>$v['hash']));
                    system("echo -e '\\033[32m成功保存内容链接:\\033[0m \\033[34m".$v['url']."\\033[0m'");

                }
            }

        }else{
            update($table_name, array('need_push'=>'empty'), array('hash'=>$v['hash']));
            system("echo -e '匹配内容为空: \\033[32m".$v['url']."\\033[0m'");
        }

    }
}

// echo "\n\n------------------------\n\n";
// print_r($GLOBALS['run_sql']);