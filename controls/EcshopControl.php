<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

require APP . 'controls/BaseControl.php';
require APP . 'models/TaskModel.php';


/**
 * Ecshop采集专用接口
 */

class EcshopControl extends _BaseControl
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'Ecshop采集专用接口';
        $str = $this->_getTaskList();print_r($str);print_r($GLOBALS['run_sql']);
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