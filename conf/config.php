<?php


/**
 * 系统配置
 */
/**
 * 访问方式：PATH_INFO 和 QUERY_STRING
 */
$_CONFIGS['system']['protocol']   = 'PATH_INFO';
$_CONFIGS['system']['index']      = '';
$_CONFIGS['system']['log_output'] = 'none';
$_CONFIGS['system']['timezone']   = 'PRC';

/**
 * 数据库配置
 */
$_CONFIGS['db']['default']['hostname'] = 'localhost';
$_CONFIGS['db']['default']['username'] = 'root';
$_CONFIGS['db']['default']['password'] = '123456';
$_CONFIGS['db']['default']['database'] = 'xspider';
$_CONFIGS['db']['default']['port']     = '3306';
$_CONFIGS['db']['default']['char_set'] = 'utf8';

/**
 * 应用配置
 */
$_CONFIGS['app']['environment'] = 'development';
$_CONFIGS['app']['site_domain'] = 'http://api.doucaor.com/';
$_CONFIGS['app']['src_url']     = 'statics/';

// 设置输出字符:utf-8
header("Content-Type:text/html;charset=utf-8");
