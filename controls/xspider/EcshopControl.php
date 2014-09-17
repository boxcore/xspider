<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

require APP . 'controls/xspider/_XspiderControl.php';


/**
 * Ecshop采集专用接口
 */

class EcshopControl extends _XspiderControl
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'Ecshop采集专用接口';
        $str = $this->_getTaskList();//print_r($str);print_r($GLOBALS['run_sql']);

        /* phpQuery Demo  */
        // phpQuery::newDocumentFile('http://www.tomdurrie.com/ding-g63517.html');
        // $test =  pq(".activity")->html();
        // echo trim($test);

        /* xml 获取 */
        // phpQuery::newDocumentFile('http://www.helloweba.com/feed');
        // $title_list = pq('item>title');print_r($title_list);exit;
        // foreach($title_list as $li){
        //     echo pq($li)->html();
        //     echo '<br/>';
        // }
        // 
        

        /**
         * 获取维美达链接列表
         */
        // phpQuery::newDocumentFile('http://www.tomdurrie.com/search.php?page=1');
        // $goods_list = pq('.hoverlist');
        // foreach($goods_list as $li){
        //     $goods[] = array(
        //             'url' => pq($li)->find('a')->attr('href'),
        //             'image' => 'http://www.tomdurrie.com/'.pq($li)->find('img')->attr('src'),
        //         );
        // }
        // print_r($goods);

        /**
         * 获取单个产品内容
         */
        phpQuery::newDocumentFile('http://www.tomdurrie.com/ding-g63256.html');
        $goods_gallerys = pq('.gallery>#demo>#demo1>ul>li');
        foreach($goods_gallerys as $li){
            $goods_images[] = array(
                'org_img' => 'http://www.tomdurrie.com/'.pq($li)->find('a')->attr('rev'),
                'thumb_img' => 'http://www.tomdurrie.com/'.pq($li)->find('img')->attr('src'),
            );
        }
        // 说明源id大于47900是无水印的 http://www.tomdurrie.com/search.php?page=380 前判读吧..
        $goods_info = array(
            'title' => pq('h1')->html(),
            'cat_name' => pq('#ur_here>.f_l>a:eq(1)')->html(),
            'org_id' => pq('input[name="id"]')->attr('value'),
            'sn' => pq('.props>dl:eq(0)>dd')->html(),
            'brand' => pq('.props>dl:eq(1)>dd')->html(),
            'price' => pq('#ECS_SHOPPRICE')->html(),
            'detail' => '<table>' . pq('div>table')->html() . '</table>',
            'images' => $goods_images,
        );
        
        print_r($goods_info); 


    }

    /**
     * 获取单个任务列表
     *
     * @author boxcore
     * @date   2014-09-17
     * @return array     [description]
     */
    protected function _getTaskList( ){
        $mark = 'qsbk';
        $task_list = array();
        if( !empty($mark) ) {
            $taskModel = new TaskModel();
            $task_id = $taskModel->getIdByMark($mark);
            if($task_id>0){
                $task_list = $taskModel->getList(array('task_id'=>$task_id));
                if(!empty($task_list)){
                    return $task_list[0];
                }
            }
        }

        return false;
    }

    public function getArticle() {
        header('Content-type: application/json');
        $_out = array(
            'status'=>'error',
            'msg'=>'参数错误!',
            'eo' => '0',
        );

        $keygen = input('keygen');
        $token = input('token');

        if( ($keygen == '19890110001aFk5') && ($token == 'yjmhntgbYyhGTByjghTG45H') ){
            $limit =  input('limit') ? input('limit') : 20;
            $order = input('order') ? input('order') : '';
            $configs = array(
                'date' => input('date') ? input('order') : '',
                'cid' => 4,
                'has_pic' => input('has_pic') ? input('has_pic') : 0,
            );
            $where = $this->_getWhere($configs);
            $sql = 'select * from task_contents '.$where.
                    'order by created_time desc '.
                    'limit '.$limit;
            $data = get_data($sql);
            if(!empty($data)){
                foreach($data as $v){
                    $_list[] = array(
                        'id'=>$v['id'],
                        'title'=>$v['title'],
                        'keywords'=>$v['keywords'],
                        'description'=>$v['description'],
                        'image'=>$v['pic'],
                        'content'=> $v['content'],
                        'hash'=>$v['url_hash'],
                        'utc_time'=>$v['created_time'],
                    );
                }
                $_out['list_data'] = $_list;
                $_out['status'] = 'success';
                $_out['msg'] = '成功获取数据';
            }else{
                $_out['msg'] = '暂无数据';
            }
            
        }else{
            $_out['msg'] = '验证错误';
        }

        echo json_encode($_out, true);
    }

    protected function _getWhere($configs){
        $where = 'WHERE TRUE ';

        if(!empty($configs['cid'])){
            $where .= prepare('AND `list_id` = ?i ', array($configs['cid']) );
        }
        if(!empty($configs['date'])){
            $where .= prepare('AND `created_time` >= ?s AND `created_time` <= ?s ', array($configs['date'].' 00:00:00', $configs['date'].' 23:59:59') );
        }

        return $where;
    }
}