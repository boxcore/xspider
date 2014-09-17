<?php

// 默认超时
set_time_limit( 0 );

// 定义应用目录
define('APP',  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// 载入框架引导文件
require APP . 'system/_shell.php';
require APP . 'funcs/spider.fn.php';
require APP . 'models/ContentModel.php';

system('echo -e "\033[32m开始获取队列... \033[0m"');
$contentModel = new ContentModel();

$configs = array(
    'need_push' => 'yes'
);

$url_list = $contentModel->getUrlList($configs, 'LIMIT 50 ');
$url_count = count($url_list);

system('echo -e "\033[32m 获取到'.$url_count.'条要采集的内容... \033[0m"');

if(!empty($url_list)){
    foreach($url_list as $v){
        $tmp_url_data[$v['task_list_id']][] = $v;
    }

    foreach($tmp_url_data as $ko=>$vo){
        $url_info = get_line(prepare('select * from task_list where id=?i limit 1', array($ko)));

        $content_rules = $url_info['content_rules'];
        $content_rules = json_decode($content_rules,true);
        $chatset = $content_rules['charset'];

        if(!empty($content_rules['type'])){
            
            foreach($vo as $va){
                system("echo -e '获取内容链接: \\033[32m".$va['url']."\\033[0m'");
                $html = '';
                $html = http_client_request( $va['url'] );
                if( $chatset != 'utf-8' ){
                    iconv($chatset, "UTF-8", $html);
                }

                if(empty($html)){
                    if( $va['error_time'] >=3 ){
                        update('task_url', array('need_push'=>'no'), array('hash'=>$va['hash']));
                    }else{
                        update('task_url', array( 'error_time' => $va['error_time']+1 ), array('hash'=>$va['hash']) );
                    }
                     system("echo -e '获取内容链接: \\033[32m".$va['url']."\\033[0m失败".($va['error_time']+1) ."次' ");
                    continue;
                }


                $content_data = get_content($html, $content_rules);
                
                if( !empty($content_data) && ( !empty($content_data['content']) || !empty($content_data['pic']) ) ) {
                    $_save = array(
                        'list_id' => $ko,
                        'content' => $content_data['content'],
                        'pic' => $content_data['pic'],
                        'url_id' => $va['id'],
                        'url_hash' => $va['hash'],
                        'url_link' =>  $va['url'],
                    );

                    $last_id = insert('task_contents', $_save);
                    if($last_id){
                        update('task_url', array('need_push'=>'no'), array('hash'=>$va['hash']));
                        system("echo -e '成功保存内容链接: \\033[32m".$va['url']."\\033[0m'");
                    }
                }else{
                    update('task_url', array('need_push'=>'empty'), array('hash'=>$va['hash']));
                    system("echo -e '匹配内容为空: \\033[32m".$va['url']."\\033[0m'");
                }
            }
        }
    }
}