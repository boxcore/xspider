<?php if ( !defined('BOXCORE') ) exit('No direct script access allowed');

class TaskModel {
    public function __construct() {

    }

    public function getList($configs = array(), $limit = '') {
        $_order = ' ORDER BY `id` DESC ';
        $_where = $this->__getWhere($configs);
        $sql = 'SELECT * FROM `task_list` '. $_where . $_order . $limit;
        $data = get_data($sql);
        return $data;
    }

    /**
     * 获取组装条件
     *
     * @author boxcore
     * @date   2014-06-02
     * @param  array      $configs [description]
     * @return [type]              [description]
     */
    private function __getWhere($configs = array() ) {
        $where = ' WHERE 1=1 ';

        if( isset($configs['task_id']) && !empty($configs['task_id']) ) {
            $where .= " AND `id` IN( {$configs['task_id']} ) ";
        }

        if ( isset($configs['cat_id']) && !empty($configs['cat_id']) ) {
            $where .= prepare(' AND `cat_id` = ?i ', array( $configs['cat_id'] ) );
        }

        return $where;
    }
}