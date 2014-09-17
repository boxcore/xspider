<?php if ( !defined('BOXCORE') ) exit('No direct script access allowed');

class ContentModel {
    public function __construct() {

    }

    public function getUrlList($configs,$limit='LIMIT 20 '){
        $where = $this->__getWhere($configs);
        $sql = 'SELECT * FROM task_url '.$where.
                'ORDER BY id '.$limit;
        $data = get_data($sql);
        return $data;
    }

    private function __getWhere($configs){
        $where = 'WHERE TRUE ';

        if(isset($configs['need_push'])){
            $where .= prepare('AND need_push = ?s ', array($configs['need_push']));
        }

        if(isset($configs['task_list_id'])){
            $where .= prepare('AND task_list_id = ?i ', array($configs['task_list_id']));
        }

        if(isset($configs['type'])){
            $where .= prepare('AND type = ?i ', array($configs['type']));
        }

        if(isset($configs['date'])){
            $where .= prepare('AND created_time >= ?s AND created_time <= ?s ', array( $configs['date']." 00:00:00", $configs['date']." 23:59:59"));
        }

        return $where;
    }
}