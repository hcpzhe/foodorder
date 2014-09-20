<?php
return array(
// 	'AUTOLOAD_NAMESPACE' => array('Addons' => ADDON_PATH), //扩展模块列表
	'DEFAULT_TIMEZONE'			=>	'PRC', //时区
	'URL_PATHINFO_DEPR'			=>	'-', //参数之间的分割符号  默认是'/'
	'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'                 =>  2, // 1:PATHINFO  2:rewrite 如果你的环境不支持PATHINFO 请设置为3
    'SHOW_PAGE_TRACE'           =>  true,//显示调试信息
    
    'DB_TYPE'                   =>  'mysqli',
    'DB_HOST'                   =>  '192.168.1.9',
    'DB_NAME'                   =>  'foodorder',
    'DB_USER'                   =>  'wqseo',
    'DB_PWD'                    =>  'wqseo',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'fdo_',
	
//	'TOKEN_ON'					=>	TRUE,
//	'TOKEN_NAME'				=>	'__hash__',

//	'TMPL_ACTION_ERROR'			=>	TMPL_PATH.'dispatch_jump.tpl', // 错误跳转对应的模板文件
//	'TMPL_ACTION_SUCCESS'		=>	TMPL_PATH.'dispatch_jump.tpl', // 成功跳转对应的模板文件
	'PW_PREFIX'					=>	'igh9d', //用户密码前缀
	'PW_SUFFIX'					=>	'u83fk', //用户密码后缀
	'LIST_ROWS'					=>	20,	//每页的条数
	
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__ADDONS__' => __ROOT__ . '/Public/addons',
		'__IMG__'    => __ROOT__ . '/Public/images',
		'__CSS__'    => __ROOT__ . '/Public/css',
		'__JS__'     => __ROOT__ . '/Public/js',
	),
);
