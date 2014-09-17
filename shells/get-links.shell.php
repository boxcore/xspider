<?php

// 默认超时
set_time_limit( 0 );

// 定义应用目录
define('APP',  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// 载入框架引导文件
require APP . 'system/_shell.php';
require APP . 'funcs/spider.fn.php';
require APP . 'models/TaskModel.php';

system('echo -e "\033[32m开始获取队列... \033[0m"');
$taskModel = new TaskModel();
$task_list = $taskModel->getList();
$task_count = count($task_list);

system('echo -e "获取到\033[32m['.$task_count.']\033[0m个任务队列, 开始抓取链接列表..."');
foreach($task_list as $v){

    $task_list_id = $v['id'];
    $task_status = $v['status'];
    $chatset = $v['charset'];
    $rules = array();
    
    if($task_status == 'yes'){
        system("echo -e '开始抓取\\033[34m[".$v['node_name']."]\\033[0m...'");
        $rules = json_decode($v['link_rules'], true);

        // 获取内容的链接
        $link_list = array();
        $link_list = get_link_list( $rules['list_rule'] );
        $link_list_count = count($link_list);

        
        system("echo -e '获取到\\033[32m[".$link_list_count."]\\033[0m个列表列表链接,准备获取文章链接...'");
        foreach( $link_list as $vo ){

            system("echo -e '获取列表内容链接: \\033[32m".$vo."\\033[0m'");
            $content = '';
            $content = http_client_request( $vo );
            if( $chatset != 'utf-8' ){
                iconv($chatset, "UTF-8", $content);
            }
            // print_r($content);exit;
            $target_urls = array();
            $target_urls = get_content_url_list($content, $rules['list_area'] );

            // 探测链接失败
            if( empty( $target_urls ) ) {
                system("echo -e '探测链接列表失败: \\033[31m".$vo."\\033[0m'");
                $result_errr = insert_log( $vo, '探测链接列表失败' );
            }else{

                insert_urls( $target_urls, $task_list_id, true );
            }
        }  
    }

    

}


