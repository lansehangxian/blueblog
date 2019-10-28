<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2019-02-01
 * blueblog.lansehangxian.com
*/
/*核心公共文件*/

if (!defined('IN_LSHX_BLOG'))
{
	die('Access Denied');
}

define('LSHX_BLOG_ROOT', str_replace('includes','',str_replace("\\", '/', dirname(__FILE__))));
//定义博客系统的根路径

define('SLSYS','NORMAL');
//定义博客系统的运行环境

require(LSHX_BLOG_ROOT.'/includes/'.SLSYS.'.core.php');

?>