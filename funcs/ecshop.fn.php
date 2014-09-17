<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

/**
 * ========================================================================
 * 
 * Ecshop采集函数集
 *
 * ========================================================================
 */

/**
 * 获取分类ID,无则创建
 *
 * @author boxcore
 * @date   2014-09-17
 * @param  string     $cat_name [description]
 * @return [type]               [description]
 */
function get_cat_id($cat_name=''){
    $cat_id = 0;
    $cat_name = trim($cat_name);
    if(!empty($cat_name)){
        $cat_id = get_var("SELECT `cat_id` FROM `ecs_category` WHERE `cat_name` = '{$cat_name}' LIMIT 1");
        if(empty($cat_id)){
            $cat_id = insert( 'ecs_category', array( 'cat_name'=> $cat_name ) );
        }
    }

    return $cat_id;
}

/**
 * 获取品牌ID,无则创建
 *
 * @author boxcore
 * @date   2014-09-17
 * @param  string     $brand_name [description]
 * @return [type]               [description]
 */
function get_brand_id($brand_name=''){
    $brand_id = 0;
    $brand_name = trim($brand_name);
    if(!empty($brand_name)){
        $brand_id = get_var("SELECT `brand_id` FROM `ecs_brand` WHERE `brand_name` = '{$brand_name}' LIMIT 1");
        if(empty($brand_id)){
            $brand_id = insert( 'ecs_brand', array( 'brand_name'=> $brand_name ) );
        }
    }

    return $brand_id;
}